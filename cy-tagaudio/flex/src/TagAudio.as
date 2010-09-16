import events.PanelMenuEvent;

import jardin.*;

import mx.controls.listClasses.ListBase;
import mx.managers.PopUpManager;

import util.ArrayUtilities;

import vo.MP3Player;


//AJAX CONST
public static const URL_AJAX:String = "http://localhost/cy-manifestes/library/ExeAjax.php";
public static const URL_LISTEMP3:String = "http://localhost/cy-manifestes/library/ExeAjax.php?f=GetListeMp3";
//public static const URL_AJAX:String = "../library/ExeAjax.php";
//public static const URL_LISTEMP3:String = "../library/ExeAjax.php?f=GetListeMp3";

//PLAYER VIEWS STATIC CONST
public static const PLAYER_PLAYLIST:Number = 0;
public static const PLAYER_NOW_PLAYING:Number = 1;

[Bindable]
public var mp3Player:MP3Player = MP3Player.getInstance();

[Bindable]
private var playerToggleIndex:Number;

public var mdp:Boolean=false;
		
public function getConfig(params:String):void{
	tracker.trackPageview( "/getConfig" );
	var param:Object=new Object;
	param.playlist_url = URL_LISTEMP3+params;
	trace("Application:getConfig:url:"+param.playlist_url);
	
	mp3Player.getConfig(param);
	
	playerPanel.addEventListener("panelMenuChange",onPanelMenuChange);
	mp3Player.addEventListener("onDelayViewChange",onDelayViewChange);
	mp3Player.addEventListener("onDelayError",onDelayError);
	
}

//function used to dispatch click even to base component.
private function nowPlayingGridClick(event:Event):void{
	var target:ListBase = ListBase(event.currentTarget);  				
	if(	target.selectedIndex != -1 && target.selectedIndex != mp3Player.currentTrack){
		var i:int = target.selectedIndex;
		mp3Player.getTrackAt(i);
	}
}


private function onDelayViewChange(event:Event):void{
	if( playerToggleIndex != PLAYER_NOW_PLAYING )
		playerToggleIndex = PLAYER_NOW_PLAYING
}


private function onDelayError(event:Event):void{
	mp3Player.isPaused = false;
	mp3Player.getNextTrack();
}


private function onPanelMenuChange(event:PanelMenuEvent):void{
	playerToggleIndex = event.index;
}

private function shuffleSongs():void{
	//stop the mp3_player
	mp3Player.stop();
	mp3Player.currentTrack = -1;
	var oldArray:Array = this.nowPlayingGrid.dataProvider.source;
	var newArray:Array = ArrayUtilities.randomize(oldArray);
	
	mp3Player.dataProvider.removeAll();
	mp3Player.dataProvider.source = newArray;
	//mp3Player.dataProvider.dispatchEvent(new Event("change"));
	mp3Player.play();
}

private function dataTipFunction(item:Object):String{
    var tempString:String = 'Artist: ' + item.artist + '\n' + 'Album: ' + item.album + '\n' + 'Track Title: ' + item.title;
    return tempString;
}
		
private function initApp():void{
	tracker.trackPageview( "/initApp" );
	//on ne charge pas de liste au démarage
	//getConfig("");
	TcTagExi.goTagGo();
	TcDocTitre.goTagGo();
}

public function showModif():void{
	
	if(mdp){
		/*
	    var wModif:twMesDons = twMesDons(
	        PopUpManager.createPopUp(this, twMesDons, false));
	    wModif.dg.idDoc = mp3Player.currentTrackVO.annotation;		
	    PopUpManager.centerPopUp(wModif);
	    */
	    var wOnto:twOnto = twOnto(
	        PopUpManager.createPopUp(this, twOnto, false));
	    var arrIds:Array= mp3Player.currentTrackVO.annotation.split("_");
	    wOnto.idDoc = arrIds[0];
	    wOnto.idExi	= arrIds[1];
	    wOnto.Nom.text = mp3Player.currentTrackVO.artist;
	    wOnto.Titre.text = mp3Player.currentTrackVO.album;
	    wOnto.Url.text = mp3Player.currentTrackVO.location;
	    wOnto.Branche.text = mp3Player.currentTrackVO.trackNum;
	    wOnto.Question.text = mp3Player.currentTrackVO.title.split(' * ')[2];
	    wOnto.urlAjax = URL_AJAX;	
	    PopUpManager.centerPopUp(wOnto);
	}else{
	    var wMdp:twMdp = twMdp(
	        PopUpManager.createPopUp(this, twMdp, false));
	    PopUpManager.centerPopUp(wMdp);		
	}
	
}
	
		