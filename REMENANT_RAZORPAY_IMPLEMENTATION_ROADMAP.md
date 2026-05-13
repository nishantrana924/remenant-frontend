# REMENANT RAZORPAY IMPLEMENTATION ROADMAP

## 1. EXECUTIVE SUMMARY
The Remenant Health payment system currently exists in a **High-Risk/Unsafe** state for production. While the project is visually complete, the underlying payment architecture lacks critical verification layers, exposing the business to financial loss and data integrity issues.

### Core Instabilities:
- **Financial Risk**: The absence of a signature verification route means successful payments may not be recorded, and the "Mock Payment" back-door allows users to bypass payment entirely.
- **Data Integrity**: Orders are marked as "Paid" without cryptographic proof, and the cart is cleared before verification, leading to poor recovery if a browser closes mid-transaction.
- **Operational Risk**: No inventory locking exists, allowing "overselling" of stock. No webhook system is in place to capture payments if the user's browser fails to redirect.

---

## 2. CURRENT SYSTEM AUDIT

| Component | Identified Issue | Root Cause | Severity |
| :--- | :--- | :--- | :--- |
| **Routes** | **Missing Verification Route** | The `/checkout/verify` route is absent from `web.php`. | 🟥 CRITICAL |
| **Security** | **Exposed Mock Route** | `/checkout/payment/{order}/mock` allows "faking" payments. | 🟥 CRITICAL |
| **Persistence** | **Premature Cart Clear** | `session()->forget('cart')` happens before payment success. | 🟧 HIGH |
| **Integrity** | **No Webhook Support** | No async verification for browser drop-offs. | 🟧 HIGH |
| **Architecture** | **$guarded = []** | `Order` model is vulnerable to mass-assignment manipulation. | 🟧 HIGH |
| **Inventory** | **No Stock Locking** | Stock is not reserved or reduced during the payment flow. | 🟧 HIGH |

---

## 3. CRITICAL SECURITY FIXES

### TASK-RZP-001: Remove Mock Vulnerabilities
- **Objective**: Prevent "fake" order status updates.
- **Action**: Delete `mockPayment` method in `CheckoutController` and remove the route from `web.php`.
- **Production Safety**: Ensures ONLY verified Razorpay signatures can update order status.

### TASK-RZP-002: Secure Signature Verification
- **Objective**: Cryptographically verify every payment response.
- **Action**: Implement `Razorpay\Api\Api->utility->verifyPaymentSignature()` in a dedicated `verify` route.
- **Architecture**: Compare `razorpay_order_id`, `razorpay_payment_id`, and `razorpay_signature`.

### TASK-RZP-003: Webhook Endpoint (CSRF Exempt)
- **Objective**: Capture payments even if the user closes their browser.
- **Action**: Create `WebhookController` and add to `VerifyCsrfToken` middleware `$except` array.

---

## 4. ENTERPRISE PAYMENT FLOW REBUILD

**The "Locked-State" Workflow:**
1. **Initiate**: User clicks "Place Order".
2. **Order Creation**: Create `Order` in DB as `pending`. Generate Razorpay Order ID via API.
3. **Inventory Reservation**: Temporarily reserve stock (locking).
4. **Payment Popup**: Launch Razorpay Modal.
5. **Callback/Verify**: User pays -> browser redirects to `/checkout/verify` -> Signature verified.
6. **Commit**: Mark Order as `paid`, decrement inventory permanently, **THEN** clear cart.
7. **Failover**: If browser closes, Webhook receives `payment.captured` and runs step 6 asynchronously.

---

## 5. DATABASE & ORDER INTEGRITY

### Migration Recommendations:
- **Inventory Tracking**: Add `stock` to `products` (if missing or inconsistent).
- **Transaction Logs**: Create `payment_transactions` table to store raw API responses.
- **Enums**: Standardize `status` as `['pending', 'processing', 'shipped', 'delivered', 'cancelled']`.

### Model Security:
```php
// app/Models/Order.php
protected $fillable = [
    'user_id', 'order_number', 'total_amount', 'status', 
    'payment_status', 'razorpay_order_id', 'razorpay_payment_id', 
    'razorpay_signature', 'customer_name', 'email', 'phone', 'address'
];
```

---

## 6. SERVICE-BASED ARCHITECTURE

To ensure scalability, refactor into:
- **`App\Services\RazorpayService`**: All direct SDK interactions (order creation, refunds).
- **`App\Services\InventoryService`**: Atomic stock increments/decrements.
- **`App\Services\OrderService`**: Business logic for status transitions and timeline logging.

---

## 7. FRONTEND PAYMENT STABILITY

### Unpoly Compatibility:
- Ensure the Razorpay script is loaded with `data-up-keep`.
- Prevent Unpoly from hijacking the payment redirect by using `up-follow="false"` or `window.location.href`.

### User Confidence (Trust UI):
- Add Razorpay "Secure Payment" badges to the footer and checkout summary.
- Implement a "Processing Payment" overlay to prevent double-clicks.

---

## 8. WEBHOOK SYSTEM DESIGN

**Required Events:**
- `payment.captured`: The primary success trigger.
- `payment.failed`: Log failure and notify customer.
- `refund.processed`: Sync DB when a refund is issued via Razorpay Dashboard.

**Safety Features:**
- **Replay Protection**: Verify the `X-Razorpay-Signature` header for every webhook call.
- **Idempotency**: Check if the order is already `paid` before processing the webhook.

---

## 9. TESTING & SANDBOX VALIDATION

1. **Happy Path**: Complete purchase with Test Card -> Check DB status.
2. **Drop-off Test**: Close browser before redirect -> Verify Webhook completes the order.
3. **Tamper Test**: Manually hit the verify route with fake IDs -> Verify 403/Error.
4. **Race Condition**: Try to buy 10 items when only 5 are in stock -> Verify error handling.

---

## 10. DEPLOYMENT & COMPLIANCE

### Razorpay Compliance Audit:
- **Links**: Privacy, Terms, Refund, Shipping must be visible in the footer.
- **Contact**: support@remenant.in | +91 7567776796.
- **Address**: 224, Ambika Pinnacle Mall, Lajamani Chowk, Mota Varachha, Surat, Gujarat - 394101.

### Environment Setup:
- `RAZORPAY_KEY_ID`: [LIVE_KEY]
- `RAZORPAY_KEY_SECRET`: [LIVE_SECRET]
- `RAZORPAY_WEBHOOK_SECRET`: [WEBHOOK_SECRET]

---

## 11. FINAL EXECUTION ROADMAP

### Phase 1: Security & Cleanup (Critical)
- Remove Mock Routes.
- Fix Mass Assignment in `Order` model.
- Map missing `/checkout/verify` route.

### Phase 2: Core Refactor (High)
- Implement `RazorpayService`.
- Move Cart clearing to success-callback only.
- Implement Stock reduction logic.

### Phase 3: Reliability (Medium)
- Implement Webhook Controller.
- Implement Transaction Logging.
- Add trust badges and loading states.

---

## AI EXECUTION RULES
1. **Incremental Updates**: Deploy changes to `CheckoutController` one method at a time.
2. **Safety First**: Never clear the session until signature verification is 100% successful.
3. **No UI Bloat**: Stick to the current design system; only add necessary feedback elements.
