// Set up view config
var view = {
    path: "img/",
    pad: 2,
    v: 1,
    h: 1,
    vMax: 1,
    hMax: 1
};

$(function() {
    // Load scans
    initScanPanel();
    $("#scans .panel-body").append(factory(".factory", ".scan-loading"));
    
    $.get(config.API_PATH, {action: "scans"}, function(datum) {
        if (datum.success) {
            console.log("Scans loaded! %o", datum);
            
            // Add scans
            $.each(datum.data, function(i, v) {
                addScan(v, false);
            });
            
            $("#scans .scan-loading").remove();
            
            initControls();
            
            // Select first scan
            $("#scans .thumbnail .preview").first().click();
        }
        
        else {
            console.error(datum.error);
        }
    });
});

// Initialize scan panel
function initScanPanel() {
    var $panel = $("#scans");
    
    // Panel update handler
    $panel.bind("update", function() {
        var numScans = $panel.find(".scan-thumbnail").length;
        
        $panel.find(".badge").text(numScans || "");
    });
}

// Initialize scan selection
function initScans(selector, meta) {
    /**
     * The metadata shall contain the following:
     *
     *      vMax    Maximum vertical index.
     *      hMax    Maximum horizontal index.
     */
    
    // Select scans to initialize
    var $scans = $(selector);
    
    // Iterate scans
    $scans.each(function() {
        var $scan = $(this);
        var id = $scan.data("scanId");
        var $preview = $scan.find(".preview");
        
        // Add preview image
        $preview.find("img").attr("src", scanPath(id, 0, 0, $.merge([], meta.filters)));
        
        // Preview click handler
        $preview.click(function() {
            scanId = id;
            
            console.log("Loading %o...", id);
            
            // Merge metadata into view
            $.extend(view, meta);
            
            // Reset view
            view.v = view.h = 0;
            view.filters = meta.filters;
            
            // Show scan ID
            $("#controls .scan-id").text(id);
            
            loadImg(view.v, view.h);
        });
        
        // Delete click handler
        $scan.find(".caption .delete").click(function() {
            var $this = $(this);
            
            if (confirm("Delete this scan?")) {
                var deleteId = $scan.data("scanId");
                
                console.log("Deleting scan %o...", deleteId);
                $this.attr("disabled", true).text("Deleting...");
                
                $.get(config.API_PATH, {action: "delete", id: deleteId}, function(datum) {
                    if (datum.success) {
                        console.log(datum.msg + " %o", datum.data);
                        
                        $scan.remove();
                        
                        // Select first scan if deleted current scan
                        if (scanId == deleteId) $("#scans .thumbnail .preview").first().click();
                    }
                    
                    else {
                        console.error(datum.error);
                    }
                    
                    // Trigger scan panel update
                    $("#scans").trigger("update");
                });
            }
        });
    });
    
    // Trigger scan panel update
    $("#scans").trigger("update");
}

// Initialize movement controls
function initControls() {
    $("#scan").click(function() {
        alert("Check switch and lights before continuing!");
        
        var $this = $(this);
        
        console.log("Scanning...");
        $this.attr("disabled", true).text("Scanning...");
        
        // Request scan
        $.get(config.API_PATH, {action: "scan"}, function(datum) {
            var meta = datum.data;
            
            // Scan succeeded
            if (datum.success) {
                console.log(datum.msg + " %o", meta);
                $this.attr("disabled", false).addClass("btn-success").text(datum.msg);
                
                // Clear scan message after some time
                clearTimeout($this.data("timeout"));
                $this.data("timeout", setTimeout(function() {
                    $this.removeClass("btn-success").text("Scan");
                }, 1700));
                
                // Add scan to sidebar
                addScan(meta);
            }
            
            // Scan failed
            else {
                console.error(datum.error);
            }
        });
    });
    
    $("#test").click(function() {
        var $this = $(this);
        
        console.log("Self-testing...");
        $this.attr("disabled", true).text("Self-testing...");
        
        // Request self-test
        $.get(config.API_PATH, {action: "selftest"}, function(datum) {
            // Self-test succeeded
            if (datum.success) {
                console.log(datum.msg);
                $this.attr("disabled", false).addClass("btn-success").text(datum.msg);
                
                // Clear self-test message after some time
                clearTimeout($this.data("timeout"));
                $this.data("timeout", setTimeout(function() {
                    $this.removeClass("btn-success").text("Self-test");
                }, 1700));
                
                // Show output
                alert(datum.data.join("\n"));
            }
            
            else {
                console.error(datum.error);
            }
        });
    });
    
    // Buttons
    $("#controls .rotate button").click(function() {
        var datum = $(this).data();
        
        // Rotate view and reload
        rotateView(parseInt(datum.vdelta) || 0, parseInt(datum.hdelta) || 0);
        loadImg(view.v, view.h);
    });
    
    // Keyboard
    $(window).on("keydown", function(e) {
        // Do not handle keypress twice
        if (e.defaultPrevented) return;
        
        // Rotate image
        switch (e.key) {
            case "ArrowLeft":
                rotateView(0, -1);
                break;
            case "ArrowRight":
                rotateView(0, 1);
                break;
            case "ArrowUp":
                rotateView(1, 0);
                break;
            case "ArrowDown":
                rotateView(-1, 0);
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
    var path = scanPath(scanId, v, h, $.merge([], view.filters));
    
    console.log("loading image %o", path);
    
    // Load image
    $view.attr("src", path);
}

// Add scan to sidebar
function addScan(meta, prepend) {
    if (typeof prepend == "undefined") prepend = true;
    
    // Parse metadata
    scanId = meta.id;
    view.v = meta.vMin;
    view.h = meta.hMin;
    view.vMax = meta.vMax;
    view.hMax = meta.hMax;
    
    // Add scan to sidebar
    var $scan = factory(".factory", ".scan-thumbnail");
    $scan.data("scanId", meta.id);
    $scan.data("filters", meta.filters);
    $scan.find(".scan-id").text(meta.id);
    
    if (prepend) $("#scans .panel-body").prepend($scan);
    else $("#scans .panel-body").append($scan);
    
    initScans($scan, meta);
}

// Get path to specific scan image
function scanPath(id, v, h, filters) {
    if (typeof filters == "undefined") filters = [];
    var params = {
        filter: [],
        args: []
    };
    
    var brightness = $("#brightness").val();
    var contrast = $("#contrast").val();
    
    filters.push(["brightness", [brightness]], ["contrast", [contrast]]);
    
    // Parse filters into parameters
    $.each(filters, function(i, v) {
        params.filter.push(v[0]);
        params.args.push(v[1].join(","));
    });
    
    return config.IMG_PATH +
        "OASIS/" + id + "/Oasis" +
        padString(v, config.IMG_PAD) + "_" +
        padString(h, config.IMG_PAD) + ".jpg" +
        "&" + $.param(params);
}

// Rotate specified view by specified delta
// (Array crawling via http://stackoverflow.com/a/20000227/3402854)
function rotateView(vDelta, hDelta) {
    /**
     * Array crawling code adapted from http://stackoverflow.com/a/20000227/3402854
     *
     * Using offsets (-1, +1) on the initial and final view values
     * corrects for the 1-based index used by the images.
     */
    /*view.v = (((view.v - 1) + vDelta) % view.vMax + view.vMax) % view.vMax + 1;
    view.h = (((view.h - 1) + hDelta) % view.hMax + view.hMax) % view.hMax + 1;*/
    
    /*var vNew = view.v + vDelta;
    if (vNew < view.vMin || vNew > view.vMax) vNew = view.v;
    
    view.v = vNew;
    view.h = (((view.h) + hDelta) % view.hMax + view.hMax) % view.hMax;*/
    
    // Do not loop vertical segment
    var vNew = view.v + vDelta;
    if (vNew > view.vMax) vNew = Math.min(view.vMax, vNew);
    if (vNew < view.vMin) vNew = Math.max(view.vMin, vNew);
    
    view.v = vNew;
    
    // Calculate image count
    var hCount = view.hMax - view.hMin + 1;
    
    //var hActual = ((view.h + hDelta) % hCount + hCount) % hCount;
    var hTheory = (hDelta + view.h + hCount) % hCount;
    
    view.h = hTheory;
}

// Return original string padded to specified length
function padString(string, pad) {
    return ("0".repeat(pad) + string).slice(pad * -1);
}

$('#brightness').slider({
	formatter: function(value) {
		return 'Current value: ' + value;
	}
});

$('#contrast').slider({
	formatter: function(value) {
		return 'Current value: ' + value;
	}
});

// Clone factory item
function factory(parent, key) {
	return $(parent + " " + key).clone();
}