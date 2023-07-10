//document.body.innerHTML += ('<div id="preloader-body"><div id="cube-wrapper"><div class="cube-folding"><span class="leaf1"></span><span class="leaf2"></span><span class="leaf3"></span><span class="leaf4"></span></div><span class="loading"data-name="Loading">玩儿命加崽中…</span></div></div>');
document.body.innerHTML += ('<div id="preloader-body"><div id="cube-wrapper"><div style="position: absolute;top: 50%;left: 50%;"><div class="mdui-spinner mdui-spinner-colorful"></div></div></div></div>')
window.onload = function () {
    document.getElementById('cube-wrapper').style.display = "none";
    document.getElementById('preloader-body').style.display = "none";
}