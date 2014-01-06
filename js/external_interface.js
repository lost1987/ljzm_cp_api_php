var ExternalInterface={};

(function(){
    ExternalInterface = {
        isReady:false,

        isJsReady:function(){
            return this.isReady;
        },

        CalledByFlashMethodsAndCallBack:[{javascript_method:'',actionscript_method:''}],

        /**
         * 通过javascript的函数名 得到对应的actionscript函数名
         * @param methodName
         */
        getAsMethodByJsMethodName:function(methodName){
                var actionscriptMethodName = '';
                for(var i  = 0 ; i < this.CalledByFlashMethodsAndCallBack.length; i++){
                    if(this.CalledByFlashMethodsAndCallBack[i].javascript_method == methodName){
                        actionscriptMethodName = this.CalledByFlashMethodsAndCallBack[i].actionscript_method;
                        break;
                    }
                }
                return actionscriptMethodName;
        },

        register:function(methodNames){
             this.CalledByFlashMethodsAndCallBack = methodNames;
             for(var i =0; i< methodNames.length; i++){
                 this[methodNames[i].javascript_method] = eval(methodNames[i].javascript_method);
             }
        },

        /**
         * 供AS调用
         * @param methodName
         * @param params
         */
        call:function(methodName,params){
                this[methodName].apply(null,[params]);
        },

        getswf:function(movieName){
                if (navigator.appName.indexOf("Microsoft") != -1) {
                    return window[movieName+"_ob"];
                } else {
                    return document[movieName+"_em"];
                }
        },

        callActionScript:function(javascript_method,param,flashObjectID){
            var actionScriptMethodName = this.getAsMethodByJsMethodName(javascript_method);
            eval("this.getswf('"+flashObjectID+"')."+actionScriptMethodName+"('"+param+"')");
        }
    }

    /**
     * getswf 示例 flash 的代码必须要这么写 id和name对于不同的浏览器 是不一样的
     * <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="800" height="300" id="myFlash_ob" name="myFlash_ob" align="middle">
     <param name="allowScriptAccess" value="always" />
     <param name="movie" value="index.swf" />
     <param name="quality" value="high" />
     <param name="bgcolor" value="#ffffff" />
     <embed src="index.swf" quality="high" bgcolor="#ffffff" width="800" height="300" id="myFlash_em" name="myFlash_em"  swLiveConnect="true" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
     </object>
     */
})();
