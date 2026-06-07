<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Downloading Labels...</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 h-screen flex flex-col items-center justify-center font-sans">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 text-center max-w-md w-full">
        <div class="mb-6">
            <svg class="animate-spin h-12 w-12 text-orange-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-slate-900 mb-2">Downloading Labels...</h2>
        <p class="text-sm text-slate-500 mb-8">Please allow popups if prompted. Your labels are being downloaded individually.</p>
        
        <div class="space-y-3" id="status-container">
            <!-- Status items will be injected here -->
        </div>

        <button onclick="window.close()" class="mt-8 px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition-colors text-sm">
            Close Window
        </button>
    </div>

    <script>
        const links = @json($links);
        let currentIdx = 0;

        function downloadNext() {
            if (currentIdx < links.length) {
                const link = links[currentIdx];
                const a = document.createElement('a');
                a.href = link;
                a.target = '_blank';
                // Trigger download
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                
                const statusDiv = document.getElementById('status-container');
                statusDiv.innerHTML += `<div class="text-xs font-medium text-emerald-600 bg-emerald-50 py-2 rounded">Downloaded Label ${currentIdx + 1} of ${links.length}</div>`;
                
                currentIdx++;
                setTimeout(downloadNext, 1000); // 1 second delay between downloads
            } else {
                const statusDiv = document.getElementById('status-container');
                statusDiv.innerHTML += `<div class="text-sm font-bold text-slate-900 mt-4">All downloads initiated!</div>`;
                document.querySelector('.animate-spin').style.display = 'none';
            }
        }

        window.onload = () => {
            setTimeout(downloadNext, 1000);
        };
    </script>
</body>
</html>
