<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WordCloud Display</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            width: 100%; height: 100%;
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(135deg, #0a0a2e 0%, #0d47a1 50%, #01579b 100%);
            overflow: hidden;
        }
        #canvas-container {
            width: 100vw; height: 100vh;
            position: relative;
        }
        #wordcloudCanvas {
            width: 100%; height: 100%;
        }
        .overlay-info {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            color: #fff;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.85rem;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .title-badge {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .empty-state {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: rgba(255,255,255,0.6);
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
    <a href="<?= base_url('wordcloud') ?>" target="_blank" style="color:#90caf9;text-decoration:none;">ร่วม WordCloud</a>
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

        const colors = [
            '#ffffff','#90caf9','#80deea','#a5d6a7',
            '#fff59d','#ffcc80','#f48fb1','#ce93d8',
            '#81d4fa','#b2dfdb',
        ];

        const list = data.map(w => {
            const normalized = (w.size - minSize) / (maxSize - minSize + 1);
            const fontSize = Math.round(24 + normalized * 80);
            return [w.text, fontSize];
        });

        WordCloud(canvas, {
            list: list,
            gridSize: Math.round(16 * canvas.width / 1024),
            weightFactor: 1,
            fontFamily: 'Sarabun, sans-serif',
            fontWeight: '700',
            color: () => colors[Math.floor(Math.random() * colors.length)],
            rotateRatio: 0.4,
            rotationSteps: 2,
            backgroundColor: 'transparent',
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
