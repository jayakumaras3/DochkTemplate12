/**
 * Created by pradeep on 21-06-2015.
 */
var assetLoader = assetLoader || {};
var sound = sound || {};
(function () {
    /***
     *
     * @constructor
     */
    var SoundClass = function () {
        this._soundPlayList = {};
        this.muteVolume = false;
        this._gainNode = null;
        this.volume = 1;
        if (webAudioPlugin===true) {
            if (typeof audioContext.createGainNode === "undefined") {
                this._gainNode = audioContext.createGain();
            } else {
                this._gainNode = audioContext.createGainNode();
            }

        }
    };


    var p = SoundClass.prototype;
    /**
     *
     * @param id
     * @returns {*}
     */
    p.play = function (id) {
 	if (Object.keys(this._soundPlayList).length > 0) {
            for (var prop in this._soundPlayList) {
                if (this._soundPlayList.hasOwnProperty(prop)) {
                    this._soundPlayList[prop].pause();
                    delete this._soundPlayList[prop];
                }
            }
        }        
        this._soundPlayList = {};
      //  console.log("---------------",id)
        this._soundPlayList[id] = new assetLoader.soundInstance(this, id);
        this._soundPlayList[id].play();
        if(webAudioPlugin===false)
        {
            if(this.muteVolume ===true)
            this._soundPlayList[id].source.volume= 0;
        }
        return this._soundPlayList[id];
    };
    /**
     * To pause all the sound created by an instance
     * Eg:
     *  var soundClass = new assetLoader.soundjs();
     *  soundClass.play("one.mp3");
     *  soundClass.play("two.mp3");
     *  soundClass.pause();
     */
    p.stop = function () {
        if (Object.keys(this._soundPlayList).length > 0) {
            for (var prop in this._soundPlayList) {
                if (this._soundPlayList.hasOwnProperty(prop)) {
                    this._soundPlayList[prop].pause();
                    delete this._soundPlayList[prop];
                }
            }
        }
        this._soundPlayList = {};
    };
    /**
     * To pause all the sound created by an instance
     * Eg:
     *  var soundClass = new assetLoader.soundjs();
     *  soundClass.play("one.mp3");
     *  soundClass.play("two.mp3");
     *  soundClass.pause();
     */
    p.pause = function () {
this.resumed = false;
        if (Object.keys(this._soundPlayList).length > 0) {
            for (var prop in this._soundPlayList) {
                if (this._soundPlayList.hasOwnProperty(prop)) {
                    this._soundPlayList[prop].pause();
                }
            }
        }
    };
    /**
     * To resume all the sound created by an instance
     * Eg:
     *  var soundClass = new assetLoader.soundjs();
     *  soundClass.play("one.mp3");
     *  soundClass.play("two.mp3");
     *  soundClass.pause();
     *  soundClass.resume();
     */
    p.resume = function () {
      //  console.log(   this._soundPlayList )
	if(this.resumed ==false)
        {	if (Object.keys(this._soundPlayList).length > 0) {
                         this._soundPlayList[Object.keys(this._soundPlayList)[Object.keys(this._soundPlayList).length-1]].resume();
         	}
	this.resumed = true;
	}
    };
    /**
     * To set the volume for the audio is playing and newly playing sound
     *
     * @param {Number} no  the volume for the global context
     */
    p.setVolume = function (no) {
        this.volume = no;
        if (webAudioPlugin===true) {
            this._gainNode.gain.value = (this.muteVolume) ? 0 : this.volume;
            this._gainNode.connect(audioContext.destination);
        } else {
            if (Object.keys(this._soundPlayList).length > 0) {
                for (var prop in this._soundPlayList) {
                    if (this._soundPlayList.hasOwnProperty(prop)) {
                        this._soundPlayList[prop].volume = this.volume;
                        document.getElementById(prop).volume = this.volume;
                    }
                }
            }
        }
    };
    /**
     *  To mute the sound created by an instance
     *  var soundClass = new assetLoader.soundjs();
     *  soundClass.play("one.mp3");
     *  soundClass.pause();
     */
    p.mute = function () {
        this.muteVolume = true;

        if (webAudioPlugin===true) {
            this._gainNode.gain.value = (this.muteVolume) ? 0 : this.volume;
            this._gainNode.connect(audioContext.destination);
        } else {

                for (var prop in this._soundPlayList) {
                    
                    if (this._soundPlayList.hasOwnProperty(prop)) {
                        this._soundPlayList[prop].source.volume = 0;
                    }
                }

        }

    };

    p.unMute = function () {
        this.muteVolume = false;
        if (webAudioPlugin===true) {
            this._gainNode.gain.value = (this.muteVolume) ? 0 : this.volume;
            this._gainNode.connect(audioContext.destination);
        } else {

                for (var prop in this._soundPlayList) {
                    if (this._soundPlayList.hasOwnProperty(prop)) {
                        this._soundPlayList[prop].source.volume = 1;
                    }
                }

        }

    };

    assetLoader.soundjs = SoundClass;

}());