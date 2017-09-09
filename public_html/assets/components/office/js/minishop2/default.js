Ext.onReady(function () {
    var grid = new OfficeExt.panel.Orders();
    grid.render('office-minishop2-grid');

    var preloader = document.getElementById('office-preloader');
    if (preloader) {
        preloader.parentNode.removeChild(preloader);
    }
});