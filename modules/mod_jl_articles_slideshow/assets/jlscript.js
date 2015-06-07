/**
 * @version		$Id: $
 * @author		Codextension
 * @package		Joomla!
 * @subpackage	Module
 * @copyright	Copyright (C) 2008 - 2012 by Codextension. All rights reserved.
 * @license		GNU/GPL, see LICENSE
 */
 ////
if( typeof(JLArticleSlideshow) == 'undefined' ){
  var JLArticleSlideshow = new Class( {
    initialize:function( _jlmain, options ){
      this.setting = Object.append({
        autoStart     	: true,
        descStyle       : 'sliding',
        mainItemSelector: 'div.jl-main-item',
        navSelector     : 'li' ,
        navigatorEvent  : 'click',
        interval        :  2000,
        auto          	: false,
        navItemsDisplay : 3,
        startItem     	: 0,
        navItemHeight   : 100,
        navItemWidth    : 310,
		descOpacity:1
      }, options || {} );
      this.descriptions = [];
      var eMain      = _jlmain.getElement('.jl-main-wapper');
      var eNavigator = _jlmain.getElement('.jl-navigator-outer .jl-navigator');
      var eNavOuter  = _jlmain.getElement('.jl-navigator-outer');   
      this.currentNo  = 0;
      this.nextNo     = null;
      this.previousNo = null;
      this.fxItems  = []; 
      this.minSize  = 0;
      this.onClick = false;
      if( eMain!=null ){
        this.slides    = eMain.getElements( this.setting.mainItemSelector );
        this.maxWidth  = eMain.getStyle('width').toInt();
        this.maxHeight = eMain.getStyle('height').toInt();
        this.styleMode = this.__getStyleMode();  
        var fx =  Object.append({waiting:false, onComplete:function(){ this.onClick=false;   this.slides.setStyle("z-index",0), this.slides[this.currentNo].setStyle("z-index",3); }.bind(this) }, this.setting.fxObject );
        this.slides.each( function(item, index) { 
          item.setStyles( eval('({"'+this.styleMode[0]+'": index * this.maxSize,"'+this.styleMode[1]+'":Math.abs(this.maxSize),"display" : "block"})') );   
          this.fxItems[index] = new Fx.Morph( item,  fx );
		  if( item.getElement(".jl-description") ) {
				this.descriptions[index] = new Fx.Morph(item.getElement(".jl-description"));
				if(index!=0 && this.descriptions[index]!=null ){
					this.descriptions[index].start({"opacity":0});
				}
			}
				
        }.bind(this) );
        if( this.styleMode[0] == 'opacity' || this.styleMode[0] =='z-index' ){
          this.slides[0].setStyle(this.styleMode[0],'1');
        }
        eMain.addEvents( { 'mouseenter' : this.stop.bind(this),
                     'mouseleave' :function(e){ 
                    if(  this.setting.auto ){
                    this.play( this.setting.interval,'next', true );
                  } }.bind(this) } );
      }
	  
	  if( _jlmain.getElement(".jl-startstop")!=null ) {
					_jlmain.getElement(".jl-startstop").addEvent('click', function(){
						if( _jlmain.getElement(".jl-startstop div").hasClass("jl-stop") ){
							this.setting.auto =true;
							this.play( this.setting.interval,'next', true );
							 _jlmain.getElement(".jl-startstop div").removeClass("jl-stop").addClass("jl-start");
						}else {
							this.setting.auto =false;
							this.stop();
							 _jlmain.getElement(".jl-startstop div").removeClass("jl-start").addClass("jl-stop");	
						}
					}.bind(this) );
				} 
         if(  this.setting.auto ){
        	///  this.registerMousewheelHandler( eNavigator ); // allow to use the srcoll
         }
		 // if( this.setting.autoStart ){
			 this.start( this.setting.autoStart, _jlmain );
		// }
		 
      if( eNavigator!=null && eNavOuter!=null ){
        var modes = {
          horizontal : ['margin-left', 'width', 'height', 'navItemWidth', 'navItemHeight'],
          vertical   : ['top', 'height', 'width', 'navItemHeight', 'navItemWidth']
        }
        var mode = ( this.setting.navPos == 'bottom' || this.setting.navPos == 'top' )?'horizontal' : 'vertical'; 
    
        this.navigatorItems = eNavigator.getElements( this.setting.navSelector );
        if( this.setting.navItemsDisplay > this.navigatorItems.length ){
          this.setting.navItemsDisplay = this.navigatorItems.length;  
        }
        
        if( _jlmain.getElement(".jl-bullets")!=null  ){
          this.setting.navItemHeight = this.navigatorItems[0].offsetHeight;
          this.setting.navItemWidth  = this.navigatorItems[0].offsetWidth;
        }
        
        this.navigatorSlideSize = this.setting[modes[mode][3]];  
        eNavOuter.setStyle(modes[mode][1], this.setting.navItemsDisplay * this.navigatorSlideSize);
                eNavOuter.setStyle(modes[mode][2], this.setting[modes[mode][4]] );
                
        this.navigatorMode =  modes[mode][0];   
        this.navigatorFx = new Fx.Tween( eNavigator,{transition:Fx.Transitions.Sine.easeInOut,duration:900} );
          
         
        this.navigatorItems.each( function(item,index) {
          item.addEvent( this.setting.navigatorEvent, function(){   
          if( this.onClick ) return ;
            this.jumping( index, true );
            this.setNavActive( index, item ); 
          }.bind(this) ); 
  
            item.setStyles( { 'height' : this.setting.navItemHeight,
                        'width'  : this.setting.navItemWidth} );    
        }.bind(this) );
        // set default setting
        this.currentNo=this.setting.startItem.toInt()>this.slides.length?this.slides.length:this.setting.startItem.toInt();
        this.setNavActive( this.currentNo );
        this.slides.setStyle(this.styleMode[0] ,this.maxSize ).setStyle("z-index",0);
        this.slides[this.currentNo].setStyle(this.styleMode[0] ,this.minSize ).setStyle("z-index",3);
      }
    },
    navivationAnimate:function( currentIndex ) { 
      if (currentIndex <= this.setting.startItem 
        || currentIndex - this.setting.startItem >= this.setting.navItemsDisplay-1) {
          this.setting.startItem = currentIndex - this.setting.navItemsDisplay+2;
          if (this.setting.startItem < 0) this.setting.startItem = 0;
          if (this.setting.startItem >this.slides.length-this.setting.navItemsDisplay) {
            this.setting.startItem = this.slides.length-this.setting.navItemsDisplay;
          }
      }   
      this.navigatorFx.cancel().start( this.navigatorMode,-this.setting.startItem*this.navigatorSlideSize );  
    },
    setNavActive:function( index, item ){
      if( this.navigatorItems!=null && this.navigatorItems[index]!=null ){ 
        this.navigatorItems.removeClass('active');
        this.navigatorItems[index].addClass('active');  
        this.navivationAnimate( this.currentNo ); 
      }
    },
    __getStyleMode:function(){
      switch( this.setting.direction ){
        case 'opacity': this.maxSize=0; this.minSize=1; return ['opacity','opacity'];
        case 'replace': this.maxSize=0; this.minSize=1; return ['display','display'];
        case 'vrup':    this.maxSize=this.maxHeight;    return ['top','height'];
        case 'vrdown':  this.maxSize=-this.maxHeight;   return ['top','height'];
        case 'hrright': this.maxSize=-this.maxWidth;    return ['left','width'];
        case 'hrleft':
        default: this.maxSize=this.maxWidth; return ['left','width'];
      }
    },
    registerMousewheelHandler:function( element ){ 
      element.addEvents({
        'wheelup': function(e) {
          
          e = new Event(e).cancel(); 
            this.previous(true);
        }.bind(this),
       
        'wheeldown': function(e) {
          e = new Event(e).cancel();
        
          this.next(true);
        }.bind(this)
      } );
    },
    registerButtonsControl:function( eventHandler, objects, isHover ){
      if( objects!=null && this.slides.length > 1 ){
        for( var action in objects ){ 
          if( this[action.toString()]!=null  && objects[action]!=null ){
            objects[action].addEvent( eventHandler, this[action.toString()].bind(this, true) );
          }
        }
      }
      return this;  
    },
    start:function( isStart, _jlmain ){
      this.setting.auto = isStart;
      // if use the preload image.
	 var obj =  _jlmain.getElement('.preload');
 
      if( obj ) {
        this.preloadImages(  obj );
      } else {
        if( this.setting.auto && this.slides.length > 1 ){
            this.play( this.setting.interval,'next', true );} 
      }
	  
		 if( _jlmain.getElement(".jl-startstop")!=null ) {
				if( this.setting.auto ){
					_jlmain.getElement(".jl-startstop div").addClass("jl-start");	
				} else {
					_jlmain.getElement(".jl-startstop div").addClass("jl-stop");		
				}
		}
			
    },
    onComplete:function( obj ){  
      (function(){                                
        obj.fade('out');    
      }).delay(500);
      if( this.setting.auto && this.slides.length > 1 ){
        this.play( this.setting.interval,'next', true );} 
      
    },
    preloadImages:function( obj ){  
      var loaded=[];
      var counter=0;
      var self = this;
      var _length = this.slides.getElements('img').length;
      this.timer = setInterval( function(){
        if(counter >= _length) {   
            clearInterval(self.timer);
            self.onComplete( obj );
            return true;
        } 
      }, 200);
      this.slides.getElements('img').each( function(img, index){
        image = new Image();
        image.src=img.src;
        if( !image.complete ){          
          image.onload =function(){
            counter++;
          }
          image.onerror =function(){ 
            counter++;
          }
        }else {
          counter++;
        }
      } );
    },
    onProcessing:function( manual, start, end ){  
      this.onClick = true;
      this.previousNo = this.currentNo + (this.currentNo>0 ? -1 : this.slides.length-1);
      this.nextNo   = this.currentNo + (this.currentNo < this.slides.length-1 ? 1 : 1- this.slides.length);       
      return this;
    },
    finishFx:function( manual, currentNo ){
      if( manual ) this.stop();
      if( manual && this.setting.auto ){  
        this.play( this.setting.interval,'next', true );
      }   
      this.setNavActive( this.currentNo );  
	  if( this.descriptions[currentNo]!=null && this.descriptions[currentNo].start!=null ){
			for( i = 0; i < this.descriptions.length;i++ ){
				this.descriptions[i].start({"opacity":0});
			} 
			this.descriptions[currentNo].cancel().start({"opacity":this.setting.descOpacity});
		}
		
    },
    getObjectDirection:function( start, end ){
      return eval("({'"+this.styleMode[0]+"':["+start+", "+end+"]})");  
    },
    fxStart:function( index, obj ){
      this.fxItems[index].cancel(true, false).start( obj );
      return this;
    },
    jumping:function( no, manual ){
      this.stop();
      if( this.currentNo == no ) return;
      var object = this.onProcessing( null, manual, 0, this.maxSize );
      if( this.currentNo < no  ){
        object.fxStart( no, this.getObjectDirection(this.maxSize , this.minSize) );
        object.fxStart( this.currentNo, this.getObjectDirection(this.minSize,  -this.maxSize) );
      } else {
        object.fxStart( no, this.getObjectDirection(-this.maxSize , this.minSize) );
        object.fxStart( this.currentNo, this.getObjectDirection(this.minSize,  this.maxSize) ); 
      }
      object.finishFx( manual, no );  
      this.currentNo  = no;
    },
    next:function( manual , item){
      if( this.onClick ) return ;
      this.currentNo += (this.currentNo < this.slides.length-1) ? 1 : (1 - this.slides.length); 
      this.onProcessing( item, manual, 0, this.maxSize )
        .fxStart( this.currentNo, this.getObjectDirection(this.maxSize ,this.minSize) )
        .fxStart( this.previousNo, this.getObjectDirection(this.minSize, -this.maxSize) )
        .finishFx( manual,this.currentNo );
    },
    previous:function( manual, item ){
      if( this.onClick ) return ;
      this.currentNo += this.currentNo > 0 ? -1 : this.slides.length - 1;
      this.onProcessing( item, manual, -this.maxWidth, this.minSize )
        .fxStart( this.nextNo, this.getObjectDirection(this.minSize, this.maxSize) )
        .fxStart( this.currentNo,  this.getObjectDirection(-this.maxSize, this.minSize) )
        .finishFx( manual,this.currentNo );      
    },
    play:function( delay, direction, wait ){
      this.stop(); 
      if(!wait){ this[direction](false); }
      this.isRun = this[direction].periodical(delay,this,true);
    },stop:function(){  clearInterval(this.isRun ); clearInterval(this.isRun); }
  } );
}

