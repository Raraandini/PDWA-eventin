(function() {
    const BASE_URL = (window.EventinPageData && window.EventinPageData.baseUrl) || '';
    let html5QrCode = null, isScanning = false, isProcessing = false, currentCamId = null, audioCtx = null;
    const el = id => document.getElementById(id);

    function audio() { if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)(); return audioCtx; }
    function beep(freq, dur, type) {
        try {
            const ctx = audio(), osc = ctx.createOscillator(), gain = ctx.createGain();
            osc.type = type || 'sine'; osc.frequency.value = freq; gain.gain.value = .09;
            osc.connect(gain); gain.connect(ctx.destination); osc.start(); osc.stop(ctx.currentTime + dur);
        } catch(e) {}
    }
    function play(type) {
        if (type === 'success') { beep(720, .08); setTimeout(() => beep(980, .12), 100); }
        else if (type === 'warning') beep(390, .24);
        else beep(170, .34, 'sawtooth');
    }
    function status(text, cls) {
        el('camera-status').textContent = text;
        el('cam-dot').className = 'h-3 w-3 rounded-full ' + cls;
    }
    function overlay(type, title, message) {
        const palette = {
            success: ['text-emerald-300', 'fa-circle-check'],
            warning: ['text-orange-300', 'fa-circle-exclamation'],
            error: ['text-red-300', 'fa-triangle-exclamation']
        }[type] || ['text-slate-300', 'fa-qrcode'];
        el('overlay-icon').className = 'mx-auto flex h-20 w-20 items-center justify-center rounded-[1.75rem] bg-white/10 ' + palette[0];
        el('overlay-icon').innerHTML = '<i class="fa-solid ' + palette[1] + ' text-4xl"></i>';
        el('overlay-title').textContent = title;
        el('overlay-message').textContent = message;
        el('scan-overlay').classList.remove('hidden');
        el('scan-overlay').classList.add('flex');
    }
    function resetFeedback() {
        el('feedback-card').className = 'scanner-panel rounded-[2rem] p-6 text-center';
        el('feedback-icon').className = 'mx-auto flex h-20 w-20 items-center justify-center rounded-[1.75rem] border border-white/10 bg-white/5 text-slate-400';
        el('feedback-icon').innerHTML = '<i class="fa-solid fa-radar text-3xl"></i>';
        el('feedback-title').textContent = 'Menunggu QR Code';
        el('feedback-title').className = 'mt-5 font-display text-2xl font-black text-white';
        el('feedback-desc').textContent = 'Scanner akan menampilkan status valid, invalid, atau duplikat setelah QR terbaca.';
        el('feedback-details').classList.add('hidden');
        el('reset-timer').classList.add('hidden');
        el('scan-overlay').classList.add('hidden');
        el('scan-overlay').classList.remove('flex');
        isProcessing = false;
    }
    function addHistory(res) {
        const root = el('scan-history');
        if (root.children.length === 1 && root.textContent.includes('Belum ada')) root.innerHTML = '';
        const item = document.createElement('div');
        const cls = res.status === 'berhasil' ? 'border-emerald-400/20 bg-emerald-400/10 text-emerald-200' : res.status === 'duplikat' ? 'border-orange-400/20 bg-orange-400/10 text-orange-200' : 'border-red-400/20 bg-red-400/10 text-red-200';
        item.className = 'rounded-3xl border p-4 text-sm font-bold ' + cls;
        item.innerHTML = '<p class="font-black">' + (res.data?.nama || res.message || 'Scan') + '</p><p class="mt-1 text-xs opacity-80">' + (res.data?.event || new Date().toLocaleTimeString()) + '</p>';
        root.prepend(item);
        while (root.children.length > 6) root.lastChild.remove();
    }
    window.clearHistory = function() {
        el('scan-history').innerHTML = '<div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm font-bold text-slate-400">Belum ada scan pada sesi ini.</div>';
    };
    function showResult(res) {
        const success = res.status === 'berhasil', duplicate = res.status === 'duplikat';
        play(success ? 'success' : duplicate ? 'warning' : 'error');
        const type = success ? 'success' : duplicate ? 'warning' : 'error';
        const title = success ? 'Check-In Berhasil' : duplicate ? 'Tiket Duplikat' : 'Tiket Invalid';
        overlay(type, title, res.message || title);
        el('feedback-title').textContent = title;
        el('feedback-title').className = 'mt-5 font-display text-2xl font-black ' + (success ? 'text-emerald-300' : duplicate ? 'text-orange-300' : 'text-red-300');
        el('feedback-desc').textContent = res.message || '';
        el('feedback-icon').className = 'mx-auto flex h-20 w-20 items-center justify-center rounded-[1.75rem] bg-white/10 ' + (success ? 'text-emerald-300' : duplicate ? 'text-orange-300' : 'text-red-300');
        el('feedback-icon').innerHTML = '<i class="fa-solid ' + (success ? 'fa-circle-check' : duplicate ? 'fa-circle-exclamation' : 'fa-triangle-exclamation') + ' text-3xl"></i>';
        if (res.data) {
            el('det-name').textContent = res.data.nama || '-';
            el('det-event').textContent = res.data.event || '-';
            el('det-time').textContent = res.data.waktu || res.data.waktu_checkin || new Date().toLocaleString();
            el('feedback-details').classList.remove('hidden');
        }
        addHistory(res);
        el('reset-timer').classList.remove('hidden');
        let count = 3; el('reset-secs').textContent = count;
        const timer = setInterval(() => {
            count--; el('reset-secs').textContent = count;
            if (count <= 0) { clearInterval(timer); resetFeedback(); }
        }, 1000);
    }
    function onScanSuccess(decodedText) {
        if (isProcessing) return;
        isProcessing = true;
        el('feedback-title').textContent = 'Memvalidasi token...';
        el('feedback-icon').innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-3xl"></i>';
        fetch(BASE_URL + '/admin/scan/process', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ token_qr: decodedText })
        }).then(r => r.json()).then(showResult).catch(() => showResult({ status: 'error', message: 'Koneksi server gagal.' }));
    }
    function config() {
        const reader = el('reader');
        const w = reader.offsetWidth || 640;
        const h = reader.offsetHeight || 420;
        const box = Math.min(Math.floor(Math.min(w, h) * .68), 320);
        return { fps: 15, qrbox: { width: box, height: box }, aspectRatio: w / h };
    }
    async function startCamera() {
        status('Meminta akses kamera...', 'bg-orange-400 animate-pulse');
        try {
            const cameras = await Html5Qrcode.getCameras();
            if (!cameras.length) { status('Kamera tidak ditemukan', 'bg-red-500'); return; }
            const select = el('camera-select');
            select.innerHTML = '';
            cameras.forEach(cam => {
                const opt = document.createElement('option');
                opt.value = cam.id; opt.textContent = cam.label || 'Camera ' + (select.children.length + 1);
                select.appendChild(opt);
            });
            const rear = cameras.find(c => /back|rear|belakang/i.test(c.label));
            currentCamId = rear ? rear.id : cameras[0].id;
            select.value = currentCamId;
            select.classList.toggle('hidden', cameras.length < 2);
            select.onchange = async () => {
                if (html5QrCode && isScanning) {
                    await html5QrCode.stop();
                    currentCamId = select.value;
                    await html5QrCode.start(currentCamId, config(), onScanSuccess);
                }
            };
            el('reader-placeholder').style.display = 'none';
            html5QrCode = new Html5Qrcode('reader');
            await html5QrCode.start(currentCamId, config(), onScanSuccess);
            isScanning = true;
            status('Kamera aktif - menunggu scan', 'bg-emerald-400 animate-pulse');
            el('toggle-cam-btn').innerHTML = '<i class="fa-solid fa-stop"></i> Hentikan Kamera';
        } catch(err) {
            console.error(err);
            el('reader-placeholder').style.display = 'flex';
            status('Akses kamera ditolak / error', 'bg-red-500');
        }
    }
    async function stopCamera() {
        if (html5QrCode) {
            try { await html5QrCode.stop(); } catch(e) {}
            html5QrCode.clear(); html5QrCode = null;
        }
        isScanning = false;
        el('reader').innerHTML = '';
        el('reader-placeholder').style.display = 'flex';
        status('Scanner dihentikan', 'bg-slate-500');
        el('toggle-cam-btn').innerHTML = '<i class="fa-solid fa-camera"></i> Mulai Kamera';
    }
    window.toggleScanner = async function() { isScanning ? await stopCamera() : await startCamera(); };
})();
