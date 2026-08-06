/**
 * Created by pradeep on 19-06-2015.
 */
var assetLoader = assetLoader || {};
var sound = sound || {};
var images = images || {};
var script = script || {};
var webAudioPlugin = true;
var audioContext;
var preloaderCount = 0;
/**
 *  check whether webaudio supports or not
 *
 */
if (typeof AudioContext !== 'undefined') {
    audioContext = new AudioContext();
} else if (typeof webkitAudioContext !== 'undefined') {
    audioContext = new webkitAudioContext();
} else {
    webAudioPlugin = false;
}

(function () {

    "use strict";
    var preload = function () {
        assetLoader.EventDispatcher.call(this);
        this._loadSoundCount = 0;
        this._loadSoundArray = [];
        this._loadImageArray = [];
        this._loadScriptArray = [];
        this._audioRequest =[];
        this._loadImageCount = 0;
        this._loadScriptCount = 0;
        this._loadScriptComplete = false;
        this._loadImageComplete = false;
        this._loadsoundComplete = false;
        this.proxy = assetLoader.proxy;
        this.stopLoading = false;
        this.totalCount = 0;
        this.preloadDOM = document.getElementById("preloaderCount");
        preloaderCount = 0;
       // this.preloadDOM.innerHTML = preloaderCount + "%";
    };
    /*** extend the class with event dispatcher*****/

    var p = preload.prototype = assetLoader.extend(preload, assetLoader.EventDispatcher);
    p.unloadPreload = function () {
        this.unloadScript();
        this.unloadSound();
        this.unloadImage();
    };

    p.unloadScript = function () {
        if (this._loadScriptComplete == false) {
            if (this._loadScriptArray.length > 0) {
                for (var i = 0; i < this._loadScriptArray.length; i++) {
                    var id = this._loadScriptArray[i].id;
                    if (typeof script[id] === 'undefined') {
                        var scriptTag = document.getElementById(id);
                        scriptTag.removeEventListener('load', this.proxy(this.scriptLoadComplete, this, scriptTag, id));
                    }
                }
            }
        }
    };
    p.unloadImage = function () {
        if (this._loadImageComplete == false) {
            if (this._loadImageArray.length > 0) {
                for (var i = 0; i < this._loadImageArray.length; i++) {
                    var src = this._loadImageArray[i].src;
                    var id = this._loadImageArray[i].id;
                    if (typeof images[id] === 'undefined') {
                        var image = new Image();
                        image.src = src;
                        image.removeEventListener('load', this.proxy(this._imageComplete, this, image, id));
                    }
                }
            }
        }

    };
    p.removeAll = function () {
        images = {};
        for (var key in script) {
            if (script.hasOwnProperty(key)) {
                if (typeof script[key] == "object") {
                    document.getElementsByTagName("head")[0].removeChild(script[key]);
                    delete script[key];
                }
            }
        }
        script = {};

    };
    /**
     * public function
     *
     * To load the assets.
     *
     * <h4>Example</h4>
     *      var preloadjs = new src.preloadjs();
     *      preloadjs.loadManifest(manifest);
     *
     * @method loadManifest
     * @param {Object} manifest The Object type of the manifest.
     */
    p.loadManifest = function (manifest) {
        this.totalCount = manifest.length;
        for (var i = 0; i < manifest.length; i++) {
            var extension = manifest[i].src.split(".");
            extension[1] = extension[1].toLowerCase();
            if (extension[1] === "mp3" || extension[1] === "wav" || extension[1] === "ogg") {
                this._loadSoundArray.push(manifest[i]);
            }
            if (extension[1] === "png" || extension[1] === "jpeg" || extension[1] === "jpg") {
                this._loadImageArray.push(manifest[i]);
            }
            if (extension[1] === "js") {
                this._loadScriptArray.push(manifest[i]);
            }
        }
        this._loadSound();
        this._loadImage();
        this._loadScript();

    };
    /**
     * private function
     *
     * To load the script which we split in the manifest
     * @method _loadScript
     *
     */


    p._loadScript = function () {
        if (this._loadScriptArray.length > 0) {
            for (var i = 0; i < this._loadScriptArray.length; i++) {
                var src = this._loadScriptArray[i].src;
                var id = this._loadScriptArray[i].id;
                if (typeof script[id] === 'undefined') {
					
                    var scriptTag = document.createElement("script");
                    scriptTag.src = src;
                    scriptTag.id = id;
                    scriptTag.type = "text/javascript";
                    document.getElementsByTagName('head')[0].appendChild(scriptTag);
					
				//	console.log("i am undefined"+currentContentValue.type);
				//	console.log("i am undefined"+scriptTag.src);
				//	if(currentContentValue.type=="html")
					{
						scriptTag.addEventListener('load', this.proxy(this.scriptLoadComplete, this, scriptTag, id));
					}

                }
                else {
					//console.log("i am defined"+script[id]);
                    this.scriptLoadComplete(undefined, script[id], id)
                }
            }
        } else {
            this._loadScriptComplete = true;
            this._checkComplete();
        }
    };
    /**
     * private function
     *
     * Trigger when  each  script load is complete
     * @method scriptLoadComplete
     * @param {event} e load event which triggers when we load script
     * @param {event} scriptTag script tag which we added
     * @param {id} id id of the script tag which we passed in manifest
     */
    p.scriptLoadComplete = function (e, scriptTag, id) {
        script[id] = scriptTag;
        this._loadScriptCount++;
        preloaderCount = Math.round(((this._loadImageCount + this._loadSoundCount + this._loadScriptCount) / this.totalCount) * 100);
       // this.preloadDOM.innerHTML = preloaderCount + "%";
        if (this._loadScriptCount === this._loadScriptArray.length) {
            this._loadScriptComplete = true;
            this._checkComplete();
        }


    };
    /**
     * private function
     *
     * To load the image which we split in the manifest
     * @method _loadImage
     *
     */
    p._loadImage = function () {
        if (this._loadImageArray.length > 0) {
            for (var i = 0; i < this._loadImageArray.length; i++) {
                var src = this._loadImageArray[i].src;
                var id = this._loadImageArray[i].id;
                if (typeof images[id] === 'undefined') {
                    var image = new Image();
                    image.src = src;
                    image.addEventListener('load', this.proxy(this._imageComplete, this, image, id));
                }
                else {
                    this._imageComplete(undefined, images[id], id);
                }
            }
        } else {
            this._loadImageComplete = true;
            this._checkComplete();
        }
    };
    /**
     * private function
     *
     * Trigger when  each  image load is complete
     * @method _imageComplete
     * @param {event} e load event which triggers when we load image
     * @param {event} image image tag which we added
     * @param {id} id id of the image tag which we passed in manifest
     */
    p._imageComplete = function (e, image, id) {
        images[id] = image;
        this._loadImageCount++;
        preloaderCount = Math.round(((this._loadImageCount + this._loadSoundCount + this._loadScriptCount) / this.totalCount) * 100);
       // this.preloadDOM.innerHTML = preloaderCount + "%";
        if (this._loadImageCount === this._loadImageArray.length) {
            this._loadImageComplete = true;
            this._checkComplete();
        }
    };
    /**
     * private function
     * Here we are checking whether browser support webaudio or not. if not it will fallback
     * native html audio. Currenlty Chrome,Safari (greater than 6) ,Firefox will support webaudio.
     * For IE it will create audio tag.
     *
     * To load the sound which we split in the manifest
     *
     * @method _loadSound
     *
     */
    p.unloadSound=function()
    {
        if(this._loadsoundComplete==false)
        {
            for (var i = 0; i < this._loadSoundArray.length; i++) {
                var id = this._loadSoundArray[i].id;
                if (webAudioPlugin == true) {
                    if (typeof sound[id] == 'undefined') {
                        this._audioRequest[id].removeEventListener('load', this.proxy(this._soundComplete, this, this._audioRequest[id], id), false);
                    }
                }
                else {
                    var audio =document.getElementById(id)
                    audio.oncanplaythrough=undefined;
                }
            }
        }
    }
    p._loadSound = function () {
        if (this._loadSoundArray.length > 0) {
            if (webAudioPlugin == true) {
                for (var i = 0; i < this._loadSoundArray.length; i++) {
                    var src = this._loadSoundArray[i].src;
                    var id = this._loadSoundArray[i].id;
                    if (typeof sound[id] == 'undefined') {
                        var request = new XMLHttpRequest();
                        request.id = id;
                        request.open('GET', src, true);
                        request.responseType = 'arraybuffer';
                        request.addEventListener('load', this.proxy(this._soundComplete, this, request, id), false);
                        request.send();
                        this._audioRequest[id]=request;
                    }
                    else {
                        this._loadSoundCount++;
                        preloaderCount = Math.round(((this._loadImageCount + this._loadSoundCount + this._loadScriptCount) / this.totalCount) * 100);
                       // this.preloadDOM.innerHTML = preloaderCount + "%";
                        if (this._loadSoundCount === this._loadSoundArray.length) {
                            this._loadsoundComplete = true;
                            this._checkComplete();
                        }
                    }
                }
            }
            else {
                this.seqAudioLoader();
                //this.nonWebAudioLoader();
            }
        } else {
            this._loadsoundComplete = true;
            this._checkComplete();
        }
    };
    p.seqAudioLoader = function () {
        for (var i = 0; i < this._loadSoundArray.length; i++) {
            var src = this._loadSoundArray[i].src;
            var id = this._loadSoundArray[i].id;
            if (sound[id] === undefined) {
                var audio = new Audio();
                audio.src = src;
                audio.id = id;
                sound[id] = audio;
                document.body.appendChild(audio);

                var self = this;
                audio.oncanplaythrough = function (e) {
                    self._soundComplete.call(self, e, sound[id], id)
                };

                audio.addEventListener('error', function failed(e) {

                }, true);
            }
            else {
                this._soundComplete(undefined, sound[id], id)
            }
        }
    };
    p.nonWebAudioLoader = function () {
      //  console.log(this._loadSoundCount, this._loadSoundArray.length);
        if (this._loadSoundCount < this._loadSoundArray.length) {
         //   console.log(this._loadSoundArray[this._loadSoundCount].id);
            var id = this._loadSoundArray[this._loadSoundCount].id;
            var src = this._loadSoundArray[this._loadSoundCount].src;
            if (sound[id] === undefined) {
                var audio = new Audio();
                audio.src = src;
                audio.id = id;
                sound[id] = audio;
                document.body.appendChild(audio);

                var self = this;
                audio.onplay = function () {

                };
                audio.oncanplaythrough = function () {
                    self.nonWebAudioLoaderComplete.call(self)
                };

                audio.addEventListener('error', function failed(e) {
                    // audio playback failed - show a message saying why
                    // to get the source of the audio element use $(this).src
                    switch (e.target.error.code) {
                        case e.target.error.MEDIA_ERR_ABORTED:
                            alert('You aborted the video playback.');
                            break;
                        case e.target.error.MEDIA_ERR_NETWORK:
                            alert('A network error caused the audio download to fail.');
                            break;
                        case e.target.error.MEDIA_ERR_DECODE:
                            alert('The audio playback was aborted due to a corruption problem or because the video used features your browser did not support.');
                            break;
                        case e.target.error.MEDIA_ERR_SRC_NOT_SUPPORTED:
                            alert('The video audio not be loaded, either because the server or network failed or because the format is not supported.');
                            break;
                        default:
                            alert('An unknown error occurred.');
                            break;
                    }
                }, true);
            }
            else {
                this._loadSoundCount++;
                this.nonWebAudioLoader();
            }
        }
        else {
            this._loadsoundComplete = true;
            this._checkComplete();
        }
    };
    p.nonWebAudioLoaderComplete = function () {
        this._loadSoundCount++;
        preloaderCount = Math.round(((this._loadImageCount + this._loadSoundCount + this._loadScriptCount) / this.totalCount) * 100);

       // this.preloadDOM.innerHTML = preloaderCount + "%";
        this.nonWebAudioLoader();

    };
    /**
     * private function
     *
     * Trigger when  each  audio load is complete
     * @method _soundComplete
     * @param {event} e load event which triggers when we load audio
     * @param {event} audio audio tag which we added
     * @param {id} id id of the image tag which we passed in manifest
     */
    p._soundComplete = function (e, audio, id) {
        if (webAudioPlugin) {
            audioContext.decodeAudioData(audio.response, this.proxy(this._decodedAudio, this, id));
        } else {

            sound[id] = audio;
            this._loadSoundCount++;
            preloaderCount = Math.round(((this._loadImageCount + this._loadSoundCount + this._loadScriptCount) / this.totalCount) * 100);
           // this.preloadDOM.innerHTML = preloaderCount + "%";
            if (this._loadSoundCount === this._loadSoundArray.length) {
                this._loadsoundComplete = true;
                this._checkComplete();
            }

        }
    };
    /**
     * private function
     *
     * When sound is loaded for the webaudio  the output will be a array buffer.
     * after the decode the function this function will trigger
     * @method _decodedAudio
     * @param {buffer} buffer the output after decode the audio
     * @param {id} id id of the sound tag which we passed in manifest
     */
    p._decodedAudio = function (buffer, id) {
        sound[id] = buffer;
        this._loadSoundCount++;
        preloaderCount = Math.round(((this._loadImageCount + this._loadSoundCount + this._loadScriptCount) / this.totalCount) * 100);
      //  this.preloadDOM.innerHTML = preloaderCount + "%";
        if (this._loadSoundCount === this._loadSoundArray.length) {
            this._loadsoundComplete = true;
            this._checkComplete();
        }
    };
    /**
     * private function
     *
     * Check whether all load is completed or not.
     * if complete it will trigger the dispatch event of onComplete.
     *
     * @method _checkComplete
     *
     */
    p._checkComplete = function () {

        if ((this._loadScriptComplete === true) && (this._loadImageComplete === true) && (this._loadsoundComplete === true)) {

            if (!this.stopLoading) {
              // this.preloadDOM.innerHTML = "0%";
                this.dispatchEvent("complete", this)
            }
        }
    };

    p.unload = function () {
        this.stopLoading = true;
    };

    assetLoader.preload = preload;
}());