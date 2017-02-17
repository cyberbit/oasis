// Set up view config
var view = {
    v: config.IMG_VSTART,
    h: config.IMG_HSTART,
    vMax: config.IMG_VMAX,
    hMax: config.IMG_HMAX
};

var preArgs = "filter=colorize&args=";
var scanId = "255,0,0";
var postArgs = ",64";

$(function() {
    // Initialize all the things
    initScans();
    initControls();
    
    // Select first scan
    $("#scans .thumbnail .preview").first().click();
});

// Initialize scan selection
function initScans() {
    var $scans = $("#scans .thumbnail");
    
    $scans.each(function() {
        var $scan = $(this);
        var id = $scan.data("scanId");
        var $preview = $scan.find(".preview");
        
        // Add preview image
        $preview.find("img").attr("src", scanPath(id, 1, 1));
        
        $preview.click(function() {
            scanId = id;
            view.v = view.h = 1;
            loadImg(view.v, view.h);
        });
    });
}

// Initialize movement controls
function initControls() {
    // Buttons
    $("#controls .rotate button").click(function() {
        var datum = $(this).data();
        
        // Rotate view and reload
        rotateView(view, parseInt(datum.vdelta) || 0, parseInt(datum.hdelta) || 0);
        loadImg(view.v, view.h);
    });
    
    // Keyboard
    $(window).on("keydown", function(e) {
        // Do not handle keypress twice
        if (e.defaultPrevented) return;
        
        // Rotate image
        switch (e.key) {
            case "ArrowLeft":
                rotateView(view, 0, -1);
                break;
            case "ArrowRight":
                rotateView(view, 0, 1);
                break;
            case "ArrowUp":
                rotateView(view, 1, 0);
                break;
            case "ArrowDown":
                rotateView(view, -1, 0);
                break;
            
            // No applicable key pressed
            default:
                return;
        }
        
        // Load image
        loadImg(view.v, view.h);
        
        // Consume event
        e.preventDefault();
    });
}

// Load image into target
function loadImg(v, h) {
    var $view = $("#view");
    
    // Format image path
    var path = scanPath(scanId, v, h);
    
    // Load image
    $view.attr("src", path);
}

// Get path to specific scan image
function scanPath(id, v, h) {
    return config.IMG_PATH +
                padString(v, config.IMG_PAD) + "_" +
                padString(h, config.IMG_PAD) + ".jpg" +
                "&" + preArgs + id + postArgs;
}

// Rotate specified view by specified delta
// (Array crawling via http://stackoverflow.com/a/20000227/3402854)
function rotateView(view, vDelta, hDelta) {
    /**
     * Array crawling code adapted from http://stackoverflow.com/a/20000227/3402854
     *
     * Using offsets (-1, +1) on the initial and final view values
     * corrects for the 1-based index used by the images.
     */
    view.v = (((view.v - 1) + vDelta) % view.vMax + view.vMax) % view.vMax + 1;
    view.h = (((view.h - 1) + hDelta) % view.hMax + view.hMax) % view.hMax + 1;
}

// Return original string padded to specified length
function padString(string, pad) {
    return ("0".repeat(pad) + string).slice(pad * -1);
}