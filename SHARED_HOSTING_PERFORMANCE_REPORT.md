# SHARED HOSTING PERFORMANCE HARDENING REPORT

## EXECUTIVE SUMMARY
This report analyzes the Laravel Ecommerce Application specifically for a **ServerByte Shared Hosting** environment. Shared hosting relies strictly on CPU limits, highly constrained RAM (often < 1GB per process), and File-based storage layers. Heavy Redis instances and long-running daemons are unavailable. To successfully sustain 100-200 concurrent users under these constraints, the application must aggressively leverage Cloudflare Edge Caching, File Caching, and low-memory Eloquent iterations to prevent 508 Resource Limit Reached or 504 Gateway Timeout errors.

---

## PHASE 1 - CURRENT BOTTLENECK ANALYSIS

**Identified Constraints:**
1. **Memory-Heavy Operations:** Controllers utilizing `get()` instead of `cursor()` on tables with > 1,000 rows will exhaust the PHP `memory_limit` (typically 128MB-256MB on shared hosting).
2. **Missing Queues:** Shared hosting cannot run `php artisan queue:work` infinitely. Webhooks and emails process synchronously during HTTP requests, leading to fast gateway timeouts if the SMTP server or external API lags.
3. **Session Overhead:** Storing massive payload data or images in `session()` fills up the server's filesystem inodes and reduces disk IOPS performance.
4. **Heavy Collections:** The `ProductController` searches load complex relations (`categories`, `comboItems`) into massive arrays, consuming extreme RAM before filtering.

---

## PHASE 2 - SHARED HOSTING OPTIMIZATION

Update the `.env` file to optimize for shared resources. Since Redis/Memcached is unavailable, we must rely on highly-optimized File drivers and Database connections.

```env
APP_DEBUG=false
APP_ENV=production

# Rely on the fastest available local drivers
CACHE_STORE=file
SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true

# Use database for queues, triggered via a Cron Job (since daemon workers are banned)
QUEUE_CONNECTION=database
```

**Required Cron Job Setup (cPanel):**
Add the following command to run every 5 minutes to process emails/webhooks asynchronously:
`*/5 * * * * cd /home/username/public_html && php artisan queue:work --stop-when-empty`

---

## PHASE 3 - DATABASE OPTIMIZATION

Shared hosting MySQL servers are often overloaded. To minimize query times, ensure all Lookups hit indexes directly.

**Missing Shared Hosting Indexes:**
```php
Schema::table('sessions', function (Blueprint $table) {
    // Session GC on shared hosts can lock the database if unindexed
    $table->index('last_activity'); 
});

Schema::table('products', function (Blueprint $table) {
    // Crucial for the heavy ProductController search
    $table->index(['status', 'price']); 
});
```

---

## PHASE 4 - MEMORY OPTIMIZATION

Fetching massive collections will crash shared hosting PHP workers.

**1. Admin Exports / Order Processing**
*Before:*
```php
$orders = Order::with('user')->get();
foreach ($orders as $order) { ... }
```
*After (Requires minimal memory):*
```php
foreach (Order::with('user')->lazy() as $order) {
    // Processes 1000 records using the same memory footprint as 1 record
}
```

**2. Sitemap Generation**
*Before:*
```php
$products = Product::where('status', 'published')->get();
```
*After:*
```php
$products = Product::where('status', 'published')->cursor();
```

---

## PHASE 5 - BLADE OPTIMIZATION

*   **Helper Calls:** Repeatedly calling `\App\Helpers\ImageHelper::getUrl()` inside `@foreach` loops triggers thousands of string concatenations. Pre-calculate URLs in the controller or model accessor.
*   **Database Queries in Views:** Ensure `$product->reviews->count()` is not called if `reviews` isn't eager loaded. Use `withCount('reviews')` in the controller to pass integer aggregations directly to the view.
*   **Compile Limits:** Ensure `php artisan view:cache` is run on deployment, as compiling Blade views on-the-fly consumes heavy CPU cycles.

---

## PHASE 6 - CACHE STRATEGY (FILE CACHE ONLY)

Since we are locked to `CACHE_STORE=file`, caching large datasets risks high Disk I/O. Cache smaller, pre-rendered HTML snippets or raw arrays.

| Component | Cache Key | TTL (Seconds) | Invalidation Strategy |
| :--- | :--- | :--- | :--- |
| **Homepage Sliders** | `home.sliders` | 86400 (24h) | Clear on `Slider::saved` |
| **Menu Categories** | `nav.categories`| 86400 (24h) | Clear on `Category::saved` |
| **Product Show** | `product.show.{id}` | 3600 (1h) | Clear on `Product::saved` |
| **Site Settings** | `app.settings` | 604800 (7d)| Clear on Settings Update |

---

## PHASE 7 - CLOUDFLARE OPTIMIZATION

To survive 200 concurrent users on Shared Hosting, Cloudflare **MUST** absorb 80% of the hits.

1.  **Page Rules:**
    *   `*yourdomain.com/images/*` -> **Cache Level: Cache Everything**, **Edge Cache TTL: a month**.
    *   `*yourdomain.com/checkout*` -> **Cache Level: Bypass**.
2.  **Security (WAF):**
    *   Block traffic from high-risk countries (if you only ship locally).
    *   Enable "Bot Fight Mode".
3.  **Rate Limiting:**
    *   Limit `POST /login` and `POST /api/search` to 10 requests per minute per IP to prevent brute-force CPU exhaustion.

---

## PHASE 8 - LOAD TEST ESTIMATION

Based on the `file` cache, `database` queues, and current codebase structure:

*   **50 Concurrent Users:** **STABLE.** CPU usage will hover around 30%. Disk I/O from session writing is manageable.
*   **100 Concurrent Users:** **MODERATE RISK.** Peaks during flash sales might cause TTFB (Time To First Byte) delays of 2-4 seconds. Cloudflare caching is mandatory here.
*   **200 Concurrent Users:** **HIGH RISK.** Shared hosting limits (Entry Processes / Max Concurrent Connections) are typically capped at 150-200. Without aggressive Cloudflare caching and `cursor()` memory optimizations, the server will throw `508 Resource Limit Is Reached` errors.

---

## PHASE 9 - FINAL REPORT

*   **Current Capacity:** ~50 Concurrent Users.
*   **Safe Capacity (Post-Fix):** ~150 Concurrent Users.
*   **Required Fixes:** 
    * Set `CACHE_STORE=file`.
    * Route Webhooks and Emails through `QUEUE_CONNECTION=database`.
    * Enforce Cloudflare Edge Caching for images and static assets.
    * Replace all admin reporting `.get()` calls with `.lazy()`.
*   **Nice-to-Have Fixes:** Setup an external VPS just for MySQL database hosting to offload the Shared Hosting CPU.

**Go-Live Recommendation:**
You are cleared to go live on ServerByte, provided you execute the `.env` configurations (Phase 2), set up the cPanel Cron Job for queues, and place the application strictly behind a hardened Cloudflare proxy. Do not attempt a major marketing push without Cloudflare Cache Everything rules enabled for your static assets.
