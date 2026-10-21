/* Efek salju ringan berbasis <canvas>.
 * Pemakaian: var snow = SnowFX(canvasEl, { density, min, max, scale }); snow.start(); snow.stop();
 * Simpan di: public/js/snow-fx.js
 */
window.SnowFX = function (canvas, opts) {
    opts = opts || {};
    var ctx = canvas.getContext('2d');
    var density = opts.density || 9000;   // makin kecil = makin banyak salju
    var minCount = opts.min || 30;
    var maxCount = opts.max || 140;
    var scale = opts.scale || 1;          // ukuran butir salju
    var dpr = Math.min(window.devicePixelRatio || 1, 2);

    var flakes = [], w = 0, h = 0, raf = null, running = false, wind = 0, windT = 0;
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function make(initial) {
        return {
            x: Math.random() * w,
            y: initial ? Math.random() * h : -10,
            r: (0.8 + Math.random() * 2.6) * scale,
            vy: 0.35 + Math.random() * 1.0,
            sw: Math.random() * Math.PI * 2,
            ss: 0.01 + Math.random() * 0.02,
            amp: 0.25 + Math.random() * 0.7,
            o: 0.55 + Math.random() * 0.45
        };
    }

    function resize() {
        w = canvas.clientWidth;
        h = canvas.clientHeight;
        if (!w || !h) return;
        canvas.width = Math.round(w * dpr);
        canvas.height = Math.round(h * dpr);
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        var count = Math.max(minCount, Math.min(maxCount, Math.round(w * h / density)));
        while (flakes.length < count) flakes.push(make(true));
        if (flakes.length > count) flakes.length = count;
    }

    function tick() {
        if (!running) return;
        ctx.clearRect(0, 0, w, h);

        windT += 0.004;
        wind = Math.sin(windT) * 0.35;

        ctx.shadowColor = 'rgba(90, 140, 220, .55)';
        ctx.shadowBlur = 3 * scale;

        for (var i = 0; i < flakes.length; i++) {
            var f = flakes[i];
            f.sw += f.ss;
            f.x += Math.sin(f.sw) * f.amp + wind;
            f.y += f.vy;

            if (f.y > h + 10) { flakes[i] = make(false); continue; }
            if (f.x > w + 10) f.x = -10;
            else if (f.x < -10) f.x = w + 10;

            ctx.globalAlpha = f.o;
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.arc(f.x, f.y, f.r, 0, Math.PI * 2);
            ctx.fill();
        }
        ctx.globalAlpha = 1;
        raf = requestAnimationFrame(tick);
    }

    function start() {
        if (running || reduce) return;
        running = true;
        canvas.style.display = 'block';
        resize();
        raf = requestAnimationFrame(tick);
    }

    function stop() {
        running = false;
        if (raf) cancelAnimationFrame(raf);
        raf = null;
        flakes = [];
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    window.addEventListener('resize', function () { if (running) resize(); });
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) { if (raf) cancelAnimationFrame(raf); raf = null; }
        else if (running && !raf) raf = requestAnimationFrame(tick);
    });

    return { start: start, stop: stop };
};