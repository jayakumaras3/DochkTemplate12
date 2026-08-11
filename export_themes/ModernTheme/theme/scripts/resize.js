/**
 * Created by pc3 on 12/25/2016.
 */
var windowResizeOption = "responsive"
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

function windowResizing() {
    'use strict';
    var body = document.body;
    if (windowResizeOption == "responsive") {
        // Modern responsive mode: do not letterbox to 1366x768.
        body.style.transform = "none";
        body.style.left = "0px";
        body.style.top = "0px";
        body.style.width = "100%";
        body.style.height = "100%";
        body.style.overflow = "hidden";
        return;
    }

    var windowWidth = window.innerWidth;
    var windowHeight = window.innerHeight;
    var scaleX = windowWidth / 1366;
    var scaleY = windowHeight / 768;
    var bodyWidth = 1366 * scaleX;
    var bodyHeight = 767 * scaleY;
    var leftPos = 0;
    var topPos = 0;
    if (windowResizeOption == "fullscreen") {
        body.style.transform = "scale(" + scaleX + "," + scaleY + ")";
        var diff = 1 - scaleX;
        var diff1 = 1 - scaleY
        leftPos = (1366 * diff);
        topPos = (768 * diff1);
        body.style.left = -leftPos / 2 + "px";
        body.style.top = -topPos / 2 + "px";
    } else if (windowResizeOption == "aspect") {
        var scale = (scaleX > scaleY) ? scaleY : scaleX;
        leftPos = ((bodyWidth * scaleY) - bodyWidth) / 2;
        bodyWidth = 1367;
        bodyHeight = 769;
        if ((windowWidth > bodyWidth) && (windowHeight > bodyHeight)) {
            leftPos = (windowWidth - bodyWidth) / 2;
            topPos = (windowHeight - bodyHeight) / 2;
            body.style.left = leftPos + "px";
            body.style.top = topPos + "px";
            body.style.transform = "scale(" + scale + ")";
        }
        /*else if ((windowWidth > bodyWidth) && (windowHeight < bodyHeight)) {
         leftPos = (windowWidth - bodyWidth) / 2;
         scale = windowHeight / bodyHeight;
         topPos = ((bodyHeight * scale) - bodyHeight) / 2;
         body.style.transform = "scale(" + scale + ")";
         body.style.left = leftPos + "px";
         body.style.top = topPos + "px";
         
         } else if ((windowWidth < bodyWidth) && (windowHeight > bodyHeight)) {
         topPos = (windowHeight - bodyHeight) / 2;
         scale = windowWidth / bodyWidth;
         leftPos = ((bodyWidth * scale) - bodyWidth) / 2;
         body.style.transform = "scale(" + scale + ")";
         body.style.left = leftPos + "px";
         body.style.marginTop = topPos + "px";
         } */
        else {

            //console.log(windowWidth,bodyWidth,bodyHeight,windowHeight,scaleY,scaleX,topPos,leftPos);
            var scaleX = windowWidth / bodyWidth;
            var scaleY = windowHeight / bodyHeight;
            scale = (scaleX > scaleY) ? scaleY : scaleX;
            topPos = ((bodyHeight * scale) - bodyHeight) / 2;
            leftPos = ((bodyWidth * scaleX) - bodyWidth) / 2;
            body.style.transform = "scale(" + scale + ")";
            body.style.left = leftPos + "px";
            body.style.top = topPos + "px";
        }
    } else {
        bodyWidth = 1367;
        bodyHeight = 769;

        if (windowWidth > bodyWidth) {
            leftPos = (windowWidth - bodyWidth) / 2;
        }
        if (windowHeight > bodyHeight) {
            topPos = (windowHeight - bodyHeight) / 2;
        }
        body.style.transform = "scale(" + 1 + ")";
        body.style.left = leftPos + "px";
        body.style.top = topPos + "px";
        body.style.overflow = "auto"
    }

}
window.addEventListener("resize", windowResizing);
windowResizing();