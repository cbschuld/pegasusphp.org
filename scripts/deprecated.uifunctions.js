// Here we check to see if we have access to getElementById
// if we do not have access to it we construct it dynamically
// if we do not even have document.all then we are screwed --
// we tell the user and then -- antisipate the explosion

if( ! document.getElementById ) {
	document.getElementById = function(strID) {
		var obj = null;
		if (document.layers) {
			obj = document[strID];
		} else if (document.all) {
			obj = document.all[strID];
		} else {
			alert( 'WARNING: Incompatible JavaScript DOM version for this application, please contact support' );
		}
		return $obj;
	}
}

function toggleLayer(whichLayer) {
	var s = document.getElementById(whichLayer).style;
	s.display = ( s.display == "block" ? "none" : "block" );
}

function displayLayer(whichLayer,bShow) {
	var element = document.getElementById(whichLayer);
	if( !element ) {
		alert( 'UIFunction: displayLayer: Element ' + whichLayer + ' not found' );
	}
	else {
		var s = document.getElementById(whichLayer).style;
		s.display = ( bShow ? "block" : "none" );
	}
}

function hideLayer(whichLayer) {
	displayLayer(whichLayer,false);
}

function showLayer(whichLayer) {
	displayLayer(whichLayer,true);
}

function changeClass(elem, myClass) {
	var elem = document.getElementById(elem);
	elem.className = myClass;
}

function getRefToDivMod( divID, oDoc ) {
	if( !oDoc ) { oDoc = document; }
	if( document.layers ) {
		if( oDoc.layers[divID] ) { return oDoc.layers[divID]; } else {
			for( var x = 0, y; !y && x < oDoc.layers.length; x++ ) {
				y = getRefToDivNest(divID,oDoc.layers[x].document); }
			return y; } }
	if( document.getElementById ) { return oDoc.getElementById(divID); }
	if( document.all ) { return oDoc.all[divID]; }
	return document[divID];
}


function findPosition( oLink ) {
  if( oLink.offsetParent ) {
    for( var posX = 0, posY = 0; oLink.offsetParent; oLink = oLink.offsetParent ) {
      posX += oLink.offsetLeft;
      posY += oLink.offsetTop;
    }
    return [ posX, posY ];
  } else {
    return [ oLink.x, oLink.y ];
  }
}


function testToggleLayer(whichLayer) {
	if (document.getElementById) {
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		var style3 = document.getElementById('holder').style;
		style2.display = style2.display == "block" ? "none" : "block";

	var oH = getRefToDivMod( 'holder' ); if( !oH ) { return false; }
	var oW = oH.clip ? oH.clip.width : oH.offsetWidth;
	var oT = oH.clip ? oH.clip.top : oH.offsetTop;
	var oL = oH.clip ? oH.clip.left : oH.offsetLeft;
	var oH = oH.clip ? oH.clip.height : oH.offsetHeight; if( !oH ) { return false; }

//	var pos = findPosition( getRefToDivMod( 'holder' ) );
	
	
//		alert( pos[0] );	
//		alert( pos[1] );	


//		document.getElementById(whichLayer).style.width = oW + 'px';
//		document.getElementById(whichLayer).style.height = oH + 'px';
//		document.getElementById(whichLayer).style.top = (pos[1] - 10) + 'px';

	}
	else if (document.all) {
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		style2.display = style2.display == "block" ? "none" : "block";
	}
	else if (document.layers) {
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		style2.display = style2.display == "block" ? "none" : "block";
	}
}
