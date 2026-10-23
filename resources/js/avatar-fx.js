/*!
 * AvatarFX - efek partikel untuk border foto profil
 *
 * Pemakaian:
 *   <div class="avatar-wrap" data-fx="galaksi" data-fx-pad="34" data-fx-density="1"> ... </div>
 *   AvatarFX.auto();                    // pasang ke semua elemen [data-fx]
 *   AvatarFX.set(wrapEl, 'api');        // ganti jenis efek (dipakai pratinjau)
 */
(function (global) {
    'use strict';

    var TAU = Math.PI * 2;
    var reduce = !!(global.matchMedia && global.matchMedia('(prefers-reduced-motion: reduce)').matches);

    var instances = [];
    var rafId = 0;
    var lastTs = 0;

    function rand(a, b) { return a + Math.random() * (b - a); }
    function pick(arr) { return arr[Math.floor(Math.random() * arr.length)]; }

    /* titik (x,y) berada di luar lingkaran foto? */
    function outside(i, x, y) {
        var dx = x - i.cx, dy = y - i.cy, r = i.R - 1;
        return dx * dx + dy * dy > r * r;
    }

    /* bintang 4 sudut */
    function star(c, x, y, r) {
        c.beginPath();
        c.moveTo(x, y - r);
        c.quadraticCurveTo(x, y, x + r, y);
        c.quadraticCurveTo(x, y, x, y + r);
        c.quadraticCurveTo(x, y, x - r, y);
        c.quadraticCurveTo(x, y, x, y - r);
        c.fill();
    }

    /* hati */
    function heart(c, x, y, s) {
        c.beginPath();
        c.moveTo(x, y + s * 0.9);
        c.bezierCurveTo(x - s * 1.5, y - s * 0.1, x - s * 0.7, y - s * 1.1, x, y - s * 0.35);
        c.bezierCurveTo(x + s * 0.7, y - s * 1.1, x + s * 1.5, y - s * 0.1, x, y + s * 0.9);
        c.fill();
    }

    /* =====================================================================
     * Definisi efek.
     *   n      : jumlah partikel (dikali density & ukuran)
     *   spawn  : buat partikel baru (first = true saat awal, disebar acak)
     *   step   : perbarui partikel, return false bila sudah mati
     *   draw   : gambar partikel
     *   pre    : (opsional) gambar latar efek sebelum partikel
     * ===================================================================== */
    var FX = {};

    /* ---------- DEFAULT: titik cahaya lembut ---------- */
    FX['default'] = {
        n: 8,
        spawn: function (i, first) {
            return {
                a: rand(0, TAU), r: i.R + rand(2, i.pad * 0.7),
                va: rand(-0.3, 0.3), vr: rand(2, 8) * i.u,
                life: rand(1.2, 2.2), t: first ? rand(0, 1.2) : 0,
                s: rand(1, 2.4) * i.u,
                col: pick(['#fb7185', '#fb923c', '#fda4af', '#f43f5e'])
            };
        },
        step: function (p, dt) {
            p.t += dt; p.a += p.va * dt; p.r += p.vr * dt;
            return p.t < p.life;
        },
        draw: function (c, p, i) {
            var k = Math.sin(Math.PI * p.t / p.life);
            c.globalAlpha = k * 0.9;
            c.fillStyle = p.col;
            c.beginPath();
            c.arc(i.cx + Math.cos(p.a) * p.r, i.cy + Math.sin(p.a) * p.r, p.s * (0.5 + k * 0.7), 0, TAU);
            c.fill();
        }
    };

    /* ---------- EMAS: kilau bintang ---------- */
    FX.emas = {
        n: 16,
        spawn: function (i, first) {
            return {
                a: rand(0, TAU), r: i.R + rand(-1, i.pad * 0.85),
                va: rand(-0.15, 0.15), vr: rand(1, 6) * i.u,
                life: rand(0.9, 1.8), t: first ? rand(0, 1.5) : 0,
                s: rand(2.4, 5.4) * i.u
            };
        },
        step: function (p, dt) {
            p.t += dt; p.a += p.va * dt; p.r += p.vr * dt;
            return p.t < p.life;
        },
        draw: function (c, p, i) {
            var k = Math.sin(Math.PI * p.t / p.life);
            var x = i.cx + Math.cos(p.a) * p.r, y = i.cy + Math.sin(p.a) * p.r;
            c.globalAlpha = k;
            c.fillStyle = '#f59e0b';
            star(c, x, y, p.s * k * 1.15);
            c.fillStyle = '#fffbd0';
            star(c, x, y, p.s * k * 0.5);
        }
    };

    /* ---------- PELANGI: titik warna mengorbit ---------- */
    FX.pelangi = {
        n: 20,
        spawn: function (i) {
            return {
                a: rand(0, TAU), r: i.R + rand(5, i.pad - 8),
                w: rand(0.6, 1.5), s: rand(1.6, 3.2) * i.u
            };
        },
        step: function (p, dt) { p.a += p.w * dt; return true; },
        draw: function (c, p, i) {
            var hue = (p.a * 57.3 + i.t * 70) % 360;
            for (var j = 0; j < 5; j++) {
                var a = p.a - p.w * j * 0.05;
                c.globalAlpha = (1 - j / 5) * 0.85;
                c.fillStyle = 'hsl(' + hue + ',95%,58%)';
                c.beginPath();
                c.arc(i.cx + Math.cos(a) * p.r, i.cy + Math.sin(a) * p.r, p.s * (1 - j * 0.14), 0, TAU);
                c.fill();
            }
        }
    };

    /* ---------- NEON: garis cahaya mengorbit ---------- */
    FX.neon = {
        n: 14,
        spawn: function (i) {
            return {
                a: rand(0, TAU), r: i.R + rand(6, i.pad - 6),
                w: rand(0.9, 2.2) * (Math.random() < 0.2 ? -1 : 1),
                len: rand(0.35, 0.9),
                col: pick(['#22d3ee', '#a855f7', '#22d3ee', '#e879f9'])
            };
        },
        step: function (p, dt) { p.a += p.w * dt; return true; },
        draw: function (c, p, i) {
            var from = p.w > 0 ? p.a - p.len : p.a + p.len;
            var a0 = Math.min(from, p.a), a1 = Math.max(from, p.a);
            c.globalCompositeOperation = 'lighter';
            c.lineCap = 'round';
            c.strokeStyle = p.col;
            c.globalAlpha = 0.25;
            c.lineWidth = 6 * i.u;
            c.beginPath(); c.arc(i.cx, i.cy, p.r, a0, a1); c.stroke();
            c.globalAlpha = 0.95;
            c.lineWidth = 2.2 * i.u;
            c.beginPath(); c.arc(i.cx, i.cy, p.r, a0, a1); c.stroke();
        }
    };

    /* ---------- API: bara api naik ---------- */
    FX.api = {
        n: 28,
        spawn: function (i, first) {
            var a = rand(-Math.PI * 1.1, Math.PI * 0.1);
            return {
                x: Math.cos(a) * i.R * 0.98, y: Math.sin(a) * i.R * 0.98,
                vx: rand(-6, 6) * i.u, vy: -rand(22, 58) * i.u,
                life: rand(0.7, 1.5), t: first ? rand(0, 1.2) : 0,
                s: rand(1.6, 3.8) * i.u, ph: rand(0, TAU)
            };
        },
        step: function (p, dt, i) {
            p.t += dt;
            p.x += (p.vx + Math.sin(p.t * 7 + p.ph) * 10 * i.u) * dt;
            p.y += p.vy * dt;
            return p.t < p.life;
        },
        draw: function (c, p, i) {
            var k = p.t / p.life;
            var x = i.cx + p.x, y = i.cy + p.y;
            if (!outside(i, x, y)) return;
            c.globalCompositeOperation = 'lighter';
            c.globalAlpha = (1 - k) * 0.95;
            c.fillStyle = k < 0.35 ? '#fde047' : (k < 0.7 ? '#fb923c' : '#ef4444');
            c.beginPath();
            c.arc(x, y, p.s * (1 - k * 0.7), 0, TAU);
            c.fill();
        }
    };

    /* ---------- ES: salju turun + kilau es ---------- */
    FX.es = {
        n: 26,
        spawn: function (i, first) {
            if (Math.random() < 0.22) {
                return {
                    k: 's', a: rand(0, TAU), r: i.R + rand(1, i.pad * 0.8),
                    life: rand(0.9, 1.8), t: first ? rand(0, 1) : 0, s: rand(2, 4.2) * i.u
                };
            }
            return {
                k: 'f', x: rand(0, i.S), y: first ? rand(0, i.S) : rand(-6, 0),
                vy: rand(14, 34) * i.u, sw: rand(0, TAU), sa: rand(4, 12) * i.u,
                s: rand(1.2, 3.2) * i.u
            };
        },
        step: function (p, dt, i) {
            if (p.k === 's') { p.t += dt; return p.t < p.life; }
            p.y += p.vy * dt;
            p.sw += dt * 2;
            p.x += Math.sin(p.sw) * p.sa * dt;
            return p.y < i.S + 6;
        },
        draw: function (c, p, i) {
            if (p.k === 's') {
                var k = Math.sin(Math.PI * p.t / p.life);
                var sx = i.cx + Math.cos(p.a) * p.r, sy = i.cy + Math.sin(p.a) * p.r;
                c.globalAlpha = k;
                c.fillStyle = '#38bdf8';
                star(c, sx, sy, p.s * k * 1.1);
                c.fillStyle = '#ffffff';
                star(c, sx, sy, p.s * k * 0.5);
                return;
            }
            if (!outside(i, p.x, p.y)) return;
            c.globalAlpha = 0.95;
            c.fillStyle = '#e0f2fe';
            c.strokeStyle = 'rgba(56,189,248,.85)';
            c.lineWidth = 0.9;
            c.beginPath();
            c.arc(p.x, p.y, p.s, 0, TAU);
            c.fill(); c.stroke();
            if (p.s > 2.2 * i.u) {
                c.beginPath();
                for (var j = 0; j < 3; j++) {
                    var a = j * Math.PI / 3, r = p.s * 1.9;
                    c.moveTo(p.x - Math.cos(a) * r, p.y - Math.sin(a) * r);
                    c.lineTo(p.x + Math.cos(a) * r, p.y + Math.sin(a) * r);
                }
                c.stroke();
            }
        }
    };

    /* ---------- GALAKSI: blackhole, bintang tersedot spiral ---------- */
    FX.galaksi = {
        n: 64,
        pre: function (c, i) {
            // cahaya lensa gravitasi di luar foto
            c.save();
            c.beginPath();
            c.rect(0, 0, i.S, i.S);
            c.arc(i.cx, i.cy, i.R - 1, 0, TAU);
            c.clip('evenodd');

            var pulse = 0.85 + 0.15 * Math.sin(i.t * 2);
            var g = c.createRadialGradient(i.cx, i.cy, i.R * 0.98, i.cx, i.cy, i.R + i.pad);
            g.addColorStop(0, 'rgba(236,72,153,' + (0.4 * pulse) + ')');
            g.addColorStop(0.35, 'rgba(124,58,237,' + (0.25 * pulse) + ')');
            g.addColorStop(1, 'rgba(59,130,246,0)');
            c.fillStyle = g;
            c.fillRect(0, 0, i.S, i.S);
            c.restore();
        },
        spawn: function (i, first) {
            var span = i.pad * 0.95;
            var r = first ? i.R + rand(2, span) : i.R + span * rand(0.8, 1);
            return { a: rand(0, TAU), r: r, s: rand(1, 2.6) * i.u, seed: Math.random() };
        },
        step: function (p, dt, i) {
            // makin dekat makin cepat (hukum Kepler) dan perlahan jatuh ke dalam
            var near = i.R / p.r;
            p.a += 2.2 * Math.pow(near, 1.5) * dt;
            p.r -= (3 + 9 * near * near) * i.u * dt * (0.6 + p.seed * 0.8);
            return p.r > i.R + 1;
        },
        draw: function (c, p, i) {
            var span = i.pad * 0.95;
            var f = Math.max(0, Math.min(1, (p.r - i.R) / span));     // 0 = dekat lubang, 1 = jauh
            var near = i.R / p.r;
            var trail = 0.1 + near * 0.22;

            var x = i.cx + Math.cos(p.a) * p.r;
            var y = i.cy + Math.sin(p.a) * p.r;
            var x0 = i.cx + Math.cos(p.a - trail) * (p.r + 1.5 * i.u);
            var y0 = i.cy + Math.sin(p.a - trail) * (p.r + 1.5 * i.u);

            var col = f < 0.33 ? (p.seed < 0.5 ? '#fde68a' : '#f9a8d4')
                    : f < 0.66 ? '#c084fc' : '#60a5fa';

            c.globalCompositeOperation = 'lighter';
            c.lineCap = 'round';
            c.strokeStyle = col;
            c.globalAlpha = Math.min(1, 0.35 + (1 - f) * 0.75) * Math.min(1, f * 6 + 0.2);
            c.lineWidth = p.s * (0.8 + (1 - f) * 0.9);
            c.beginPath(); c.moveTo(x0, y0); c.lineTo(x, y); c.stroke();

            c.fillStyle = '#ffffff';
            c.globalAlpha *= 0.9;
            c.beginPath(); c.arc(x, y, p.s * 0.55, 0, TAU); c.fill();
        }
    };

    /* ---------- HATI: hati melayang naik ---------- */
    FX.hati = {
        n: 11,
        spawn: function (i, first) {
            var a = rand(-Math.PI * 1.05, Math.PI * 0.05);
            return {
                x: Math.cos(a) * i.R, y: Math.sin(a) * i.R,
                vy: -rand(18, 38) * i.u, sw: rand(0, TAU), sa: rand(6, 16) * i.u,
                life: rand(1.6, 2.6), t: first ? rand(0, 2) : 0,
                s: rand(3.5, 8) * i.u,
                col: pick(['#fb7185', '#f43f5e', '#f472b6', '#fda4af'])
            };
        },
        step: function (p, dt) {
            p.t += dt; p.sw += dt * 3;
            p.y += p.vy * dt;
            p.x += Math.sin(p.sw) * p.sa * dt;
            return p.t < p.life;
        },
        draw: function (c, p, i) {
            var x = i.cx + p.x, y = i.cy + p.y;
            if (!outside(i, x, y)) return;
            var k = p.t / p.life;
            c.globalAlpha = Math.sin(Math.PI * k) * 0.95;
            c.fillStyle = p.col;
            heart(c, x, y, p.s * (0.6 + k * 0.5));
        }
    };

    /* "none" sengaja tidak punya efek */

    /* =====================================================================
     * Mesin
     * ===================================================================== */
    function layout(inst) {
        var w = inst.wrap.offsetWidth;
        if (!w || w === inst.w) return false;

        inst.w = w;
        var dpr = Math.min(global.devicePixelRatio || 1, 2);
        var S = w + inst.pad * 2;

        inst.S = S;
        inst.cx = S / 2;
        inst.cy = S / 2;
        inst.R = w / 2;
        inst.u = Math.max(0.4, w / 142);
        inst.k = Math.max(0.4, Math.min(1.3, w / 142));

        var cv = inst.canvas;
        cv.style.left = (-inst.pad) + 'px';
        cv.style.top = (-inst.pad) + 'px';
        cv.style.width = S + 'px';
        cv.style.height = S + 'px';
        cv.width = Math.round(S * dpr);
        cv.height = Math.round(S * dpr);
        inst.ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        return true;
    }

    function reset(inst) {
        inst.parts = [];
        if (inst.S) inst.ctx.clearRect(0, 0, inst.S, inst.S);

        var fx = FX[inst.type];
        inst.fx = fx || null;
        if (!fx || !inst.S) return;

        var n = Math.round(fx.n * inst.density * inst.k);
        for (var j = 0; j < n; j++) inst.parts.push(fx.spawn(inst, true));
    }

    function frame(inst, dt) {
        var c = inst.ctx, fx = inst.fx;
        inst.t += dt;

        c.setTransform(Math.min(global.devicePixelRatio || 1, 2), 0, 0, Math.min(global.devicePixelRatio || 1, 2), 0, 0);
        c.clearRect(0, 0, inst.S, inst.S);
        c.globalAlpha = 1;
        c.globalCompositeOperation = 'source-over';

        if (fx.pre) fx.pre(c, inst);

        for (var j = 0; j < inst.parts.length; j++) {
            var p = inst.parts[j];
            if (!fx.step(p, dt, inst)) {
                p = inst.parts[j] = fx.spawn(inst, false);
            }
            fx.draw(c, p, inst);
            c.globalAlpha = 1;
            c.globalCompositeOperation = 'source-over';
        }
    }

    function tick(ts) {
        var dt = Math.min(0.05, ((ts - lastTs) / 1000) || 0);
        lastTs = ts;

        for (var j = 0; j < instances.length; j++) {
            var inst = instances[j];
            if (!inst.fx || !inst.visible || !inst.S) continue;
            frame(inst, dt);
        }

        rafId = requestAnimationFrame(tick);
    }

    function start() {
        if (rafId || reduce) return;
        lastTs = performance.now();
        rafId = requestAnimationFrame(tick);
    }

    function mount(wrap, type, opts) {
        if (reduce || !wrap) return null;
        if (wrap.__fx) return wrap.__fx;

        opts = opts || {};

        var canvas = document.createElement('canvas');
        canvas.className = 'fx-canvas';
        canvas.setAttribute('aria-hidden', 'true');
        wrap.appendChild(canvas);

        var inst = {
            wrap: wrap, canvas: canvas, ctx: canvas.getContext('2d'),
            type: type, fx: null, parts: [], visible: true, t: 0,
            pad: opts.pad || 30, density: opts.density || 1,
            w: 0, S: 0, cx: 0, cy: 0, R: 0, u: 1, k: 1
        };

        wrap.__fx = inst;
        instances.push(inst);

        layout(inst);
        reset(inst);

        if (global.ResizeObserver) {
            new ResizeObserver(function () {
                if (layout(inst)) reset(inst);
            }).observe(wrap);
        }

        if (global.IntersectionObserver) {
            new IntersectionObserver(function (entries) {
                inst.visible = entries[0].isIntersecting;
            }).observe(canvas);
        }

        start();
        return inst;
    }

    var AvatarFX = {
        mount: mount,

        set: function (wrap, type) {
            var inst = wrap && wrap.__fx;
            if (!inst) { return mount(wrap, type, {
                pad: parseFloat(wrap.getAttribute('data-fx-pad')) || 30,
                density: parseFloat(wrap.getAttribute('data-fx-density')) || 1
            }); }
            inst.type = type;
            layout(inst);
            reset(inst);
            return inst;
        },

        auto: function () {
            document.querySelectorAll('[data-fx]').forEach(function (el) {
                if (el.__fx) return;
                mount(el, el.getAttribute('data-fx'), {
                    pad: parseFloat(el.getAttribute('data-fx-pad')) || 30,
                    density: parseFloat(el.getAttribute('data-fx-density')) || 1
                });
            });
        }
    };

    global.AvatarFX = AvatarFX;
})(window);