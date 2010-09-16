package 
{
	import flash.display.DisplayObjectContainer;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextFormat;
	
	import jardin.BulleTag;
	
	import mx.containers.Canvas;
	import mx.core.Application;
	
	public class Tag extends Sprite {
		
		private var _back:Sprite;
		private var _node:Object;
		private var _cx:Number;
		private var _cy:Number;
		private var _cz:Number;
		private var _color:Number;
		private var _hicolor:Number;
		private var _active:Boolean;
		private var _tf:TextField;
		private var keyword:String;
		private var _ts:Number;
		
		//You need to embed a font
		[Embed(systemFont="Tahoma",
                    fontName="myFont",
                    mimeType="application/x-font")]
        public var myFont:Class;


		public function Tag( node:Object, color:Number, hicolor:Number ){
			_node = node;
			keyword = node.name;
			_color = color;
			_hicolor = hicolor;
			_active = false;
			// create the text field
			_tf = new TextField();
			_tf.autoSize = TextFieldAutoSize.LEFT;
			_tf.selectable = false;
			// set styles
			var format:TextFormat = new TextFormat();
			format.font = "myFont";
			format.bold = true;
			format.color = color;
			_ts = (8*node.size)/50
			format.size = (_ts);
			_tf.defaultTextFormat = format;
			_tf.embedFonts = true;
			// set text
			_tf.text = node.name;
			addChild(_tf);
			// scale and add
			_tf.x = -this.width / 2;
			_tf.y = -this.height / 2;
			// create the back
			_back = new Sprite();
			_back.graphics.beginFill( _hicolor, .4 );
			_back.graphics.lineStyle( 0, _hicolor );
			_back.graphics.drawRect( 0, 0, _tf.textWidth+20, _tf.textHeight+5 );
			_back.graphics.endFill();
			addChildAt( _back, 0 );
			_back.x = -( _tf.textWidth/2 ) - 10;
			_back.y = -( _tf.textHeight/2 ) - 2;
			_back.visible = false;
			// force mouse cursor on rollover
			this.mouseChildren = false;
			this.buttonMode = true;
			this.useHandCursor = true;
			// events
			this.addEventListener(MouseEvent.MOUSE_OUT, mouseOutHandler);
			this.addEventListener(MouseEvent.MOUSE_OVER, mouseOverHandler);
			this.addEventListener(MouseEvent.MOUSE_UP, mouseUpHandler);
		}
		
		private function mouseOverHandler( e:MouseEvent ):void {
			_back.visible = true;
			_tf.textColor = _hicolor;
			_active = true;
		}
		
		private function mouseOutHandler( e:MouseEvent ):void {
			_back.visible = false;
			_tf.textColor = _color;
			_active = false;
		}
		
		private function mouseUpHandler( e:MouseEvent ):void {
			//Set the actions you want for click here
			//Alert.show("You Just clic : "+keyword);
			var bt:BulleTag=new BulleTag(keyword,3000,"yes")
			bt.addEventListener(Event.ENTER_FRAME, bt.enterFrameHandler);
			this.parent.addChild(bt);
			_tf.textColor = 0xF5D03A;
			
			
			var tc:TagCloud;
			var ciel:DisplayObjectContainer = Canvas(this.parent.parent.parent);
			var ThisTc:TagCloud = TagCloud(this.parent.parent);
			var Dst:String="";
			var params:String;
			var w:int,h:int,x:int,y:int;
			switch (ThisTc.src) {
				case "doc_titre":
					Dst="doc_questions";
			        params = _node.name;
			        w=200;h=200;x=200;y=50;
					if(ciel.getChildByName("tc_tags_docs")){
				        TagCloud(ciel.getChildByName("tc_tags_docs")).removeAllChildren();
				 	}
			        break;
			    case "doc_questions":
					Dst="tags_docs";
			        w=300;h=300;x=0;y=150;
			        params = _node.tid;
			        break;
			    case "tags_exis":
					Dst="exis_tag";
			        w=300;h=300;x=400;y=150;
			        params = _node.tid;
			        break;
			}				    
			if(Dst!=""){		
				if(ciel.getChildByName("tc_"+Dst)){
					tc = TagCloud(ciel.getChildByName("tc_"+Dst));
					tc.removeAllChildren();
				}else{
					tc = new TagCloud();
					tc.name = "tc_"+Dst;
					tc.width=w;
					tc.height=h;
					tc.x = x;
					tc.y = y;
					tc.src=Dst;
					tc.urlAjax = ThisTc.urlAjax;
				}
				tc.params=params;
				tc.goTagGo();
				ciel.addChild(tc);
			}
			//recharge la playlist des mp3
			Application.application.mp3Player.clearList();
			Application.application.getConfig("&src="+ThisTc.src+"&params="+_node.tid);
			//Application.application.mp3Player.getTrackAt(0);
		}

		// setters and getters
		public function set cx( n:Number ):void{ _cx = n }
		public function get cx():Number { return _cx; }
		public function set cy( n:Number ):void{ _cy = n }
		public function get cy():Number { return _cy; }
		public function set cz( n:Number ):void{ _cz = n }
		public function get cz():Number { return _cz; }
		public function get active():Boolean { return _active; }

	}

}
