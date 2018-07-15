//console.log('right');
jQuery(document).ready(function($){
	$('.date-picker').datepicker({

	});
	$('.map').locationpicker({
	    location: {
	        latitude: null,//40.7324319,
	        longitude: null //-73.82480777777776
	    },
	    locationName: "",
	    radius: 300,
	    zoom: 15,
	    mapTypeId: google.maps.MapTypeId.ROADMAP,
	    styles: [],
	    mapOptions: {},
	    scrollwheel: true,
	    inputBinding: {
	        latitudeInput: null,
	        longitudeInput: null,
	        radiusInput: null,
	        locationNameInput: null
	    },
	    enableAutocomplete: true,
	    enableAutocompleteBlur: false,
	    autocompleteOptions: true,
	    addressFormat: 'postal_code',
	    enableReverseGeocode: true,
	    draggable: true,
	    onchanged: function(currentLocation, radius, isMarkerDropped) {
	    	console.log(currentLocation);
	    	this.location.latitude = currentLocation.latitude;
	    	this.location.latitude = currentLocation.longitude;
	    },
	    inputBinding: {
	        locationNameInput: $('.event-location')
	    },
	    onlocationnotfound: function(locationName) {},
	    oninitialized: function (component) {},
	    // must be undefined to use the default gMaps marker
	    markerIcon: undefined,
	    markerDraggable: true,
	    markerVisible : true
	});
});
function init(){

}