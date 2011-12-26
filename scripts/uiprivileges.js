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

function togglePrivileges(chk,parentList,childrenList) {

	if( chk.checked ) {  // the checkbox is being checked by the user
	
		for( var p = 0; p < parentList.length; p++ ) { // move through all of the parent privileges

			var chkBox = document.getElementById(parentList[p]);
			chkBox.checked = true; // check all of the parent objects
		}

	} else { // the checkbox is being cleared by the user

		for( var c = 0; c < childrenList.length; c++ ) { // move through all of the parent privileges

			var chkBox = document.getElementById(childrenList[c]);
			chkBox.checked = false; // clear all of the children objects
		}
	}
}