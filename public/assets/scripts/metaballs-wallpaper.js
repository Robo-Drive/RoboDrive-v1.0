(function()
{
    
    let canvas = document.getElementById('myCanvas');
    if (!canvas) 
    {
        canvas = document.createElement('canvas');
        canvas.id = 'metaballsCanvas';
        canvas.style.position = 'absolute';
        canvas.style.inset = '0';
        canvas.style.width = '100%';
        canvas.style.height = '100%';
        canvas.style.pointerEvents = 'none';
        canvas.style.zIndex = '0';
        document.body.prepend(canvas);
    }
    
    const ctx = canvas.getContext('2d');
    let width = 0, height = 0, dpr = 1;
    
    const blobs = [];
    const BLOBS_COUNT = 15;
    
    function resize()
    {
        dpr = window.devicePixelRatio || 1;
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = Math.floor(width * dpr);
        canvas.height = Math.floor(height * dpr);
        canvas.style.width = width + 'px';
        canvas.style.height = height + 'px';
        ctx.setTransform(dpr,0,0,dpr,0,0);
    }
    
    function rand(min,max)
    { 
        return Math.random()*(max-min)+min; 
    }
    
    function createBlob(i)
    {
        const r = rand(Math.min(width,height)*0.05, Math.min(width,height)*0.18);
        const angle = rand(0,Math.PI*2);
        const speed = rand(10,60);
        
        return {
            x: rand(r, width-r),
            y: rand(r, height-r),
            r,
            vx: Math.cos(angle)*speed/60,
            vy: Math.sin(angle)*speed/60,
            phase: rand(0,Math.PI*2),
            wobble: rand(0.5,2.0),
            color: `rgba(255,45,45,0.9)`
        };
    }
    
    function init()
    {
        resize();
        blobs.length = 0;
        for(let i=0;i<BLOBS_COUNT;i++) blobs.push(createBlob(i));
        window.requestAnimationFrame(loop);
    }
    
    function drawBackground()
    {
        const g = ctx.createLinearGradient(0,0,width,height);
        g.addColorStop(0,'#000');
        g.addColorStop(1,'#050000');
        ctx.fillStyle = g;
        ctx.fillRect(0,0,width,height);
    }
    
    function drawBlobs(t)   
    {
        ctx.globalCompositeOperation = 'lighter';
        
        for(const b of blobs)
        {
            const gx = Math.cos(b.phase + t*0.0008) * (b.wobble*6);
            const gy = Math.sin(b.phase + t*0.0006) * (b.wobble*4);
            const x = b.x + gx;
            const y = b.y + gy;
            const grad = ctx.createRadialGradient(x,y,b.r*0.15,x,y,b.r);
            
            grad.addColorStop(0,'rgba(204,64,64,0.76)');
            grad.addColorStop(0.4,'rgba(204,32,32,0.56)');
            grad.addColorStop(1,'rgba(0,0,0,0)');
            
            ctx.fillStyle = grad;
            ctx.beginPath();
            ctx.arc(x,y,b.r,0,Math.PI*2);
            ctx.fill();
        }
        ctx.globalCompositeOperation = 'source-over';
    }
    
    function update(dt)
    {
        for(const b of blobs)
        {
            b.x += b.vx * dt;
            b.y += b.vy * dt;
            
            if(b.x < -b.r) b.x = width + b.r;
            if(b.x > width + b.r) b.x = -b.r;
            if(b.y < -b.r) b.y = height + b.r;
            if(b.y > height + b.r) b.y = -b.r;
        }
    }
    
    let last = performance.now();
    function loop(now)
    {
        const dt = (now - last) / 16.6667; // approx frames
        last = now;
        
        // clear
        ctx.clearRect(0,0,width,height);
        drawBackground();
        update(dt);
        drawBlobs(now);
        
        requestAnimationFrame(loop);
    }
    
    window.addEventListener('resize', resize);
    init();
})();