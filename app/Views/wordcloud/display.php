<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WordCloud Display</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            width: 100%; height: 100%;
            font-family: Inter, Sarabun, system-ui, -apple-system, 'Segoe UI', Helvetica, Arial, sans-serif;
            background: #ffffff;
            overflow: hidden;
        }
        #canvas-container {
            width: 100vw; height: 100vh;
            position: relative;
            background: #ffffff;
        }
        #wordcloudCanvas {
            width: 100%; height: 100%;
        }
        .overlay-info {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            color: #424242;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.85rem;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }
        .overlay-info a { color: #1565c0 !important; }
        .title-badge {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(80, 80, 80, 0.75);
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.35em;
            text-transform: uppercase;
        }
        .empty-state {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: rgba(90, 90, 90, 0.65);
        }
        .empty-state .icon { font-size: 5rem; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="title-badge">WordCloud</div>

<div id="canvas-container">
    <canvas id="wordcloudCanvas"></canvas>
</div>

<div class="overlay-info">
    <span id="totalCount"><?= $total ?></span> ผู้เข้าร่วม &nbsp;|&nbsp;
    <span id="updateTime">--</span> &nbsp;|&nbsp;
    <a href="<?= base_url('wordcloud') ?>" target="_blank" style="color:#1565c0;text-decoration:none;">เข้าร่วม WordCloud</a>
</div>

<div id="emptyState" class="empty-state" style="display:none;">
    <div class="icon">☁️</div>
    <div>ยังไม่มีข้อมูล WordCloud</div>
    <div style="font-size:0.85rem;margin-top:8px;">แชร์ลิงก์ให้ผู้เข้าร่วมพิมพ์คำ</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/wordcloud@1.2.2/src/wordcloud2.js"></script>
<script>
let currentWords = [];

function formatTime() {
    return new Date().toLocaleTimeString('th-TH', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
}

async function loadAndRender() {
    try {
        const res  = await fetch('<?= base_url('wordcloud/data') ?>?_=' + Date.now());
        const data = await res.json();

        document.getElementById('updateTime').textContent = formatTime();

        if (data.length === 0) {
            document.getElementById('emptyState').style.display = 'block';
            return;
        }
        document.getElementById('emptyState').style.display = 'none';

        // Refresh count
        const totalRes = await fetch('<?= base_url('wordcloud/data') ?>?count=1&_=' + Date.now());

        const canvas = document.getElementById('wordcloudCanvas');
        canvas.width  = window.innerWidth;
        canvas.height = window.innerHeight;

        const maxSize = Math.max(...data.map(d => d.size));
        const minSize = Math.min(...data.map(d => d.size));

        /* Muted tones — enough contrast on white background */
        const colors = [
            '#4a6b52', '#5c7d64', '#6b8e7a', '#3d5c44',
            '#4a6d8c', '#5a7a9e', '#5c6b8a', '#6b5b8c',
            '#a85c4a', '#9a6b4a', '#8b7355', '#7d6b55',
            '#5a5a58', '#6d6d6a', '#4a5568',
        ];

        const list = data.map(w => {
            const normalized = (w.size - minSize) / (maxSize - minSize + 1);
            const fontSize = Math.round(22 + normalized * 76);
            return [w.text, fontSize];
        });

        const gridBase = Math.max(5, Math.round(7 * canvas.width / 1024));

        WordCloud(canvas, {
            list: list,
            gridSize: gridBase,
            weightFactor: 1,
            fontFamily: 'Inter, Sarabun, system-ui, sans-serif',
            fontWeight: (word, weight, fontSize) => (fontSize >= 52 ? '600' : '500'),
            color: (word, weight, fontSize) => colors[Math.floor(Math.random() * colors.length)],
            rotateRatio: 0,
            minRotation: 0,
            maxRotation: 0,
            ellipticity: 0.82,
            backgroundColor: '#ffffff',
            wait: 10,
        });
    } catch(e) {
        console.error('WordCloud load error:', e);
    }
}

// Initial load
loadAndRender();

// Auto-refresh every 10 seconds
setInterval(loadAndRender, 10000);

// Resize handler
window.addEventListener('resize', () => {
    const canvas = document.getElementById('wordcloudCanvas');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    loadAndRender();
});
</script>
</body>
</html>
