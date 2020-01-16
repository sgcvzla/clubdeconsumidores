/*
	Proxy javascript (Zoom Web Services)
	(c 2011) Carlos De Oliveira - Zoom International Services
	cardeol@gmail.com
	
	Requires: Last version of jQuery.
	
	Signature:  $.ZoomWebServices(url, settings);
	
	** Parameters **
	url: Web services url
	settings: ( array ) 
		* service: (Name of service). Requeired
		* data: parameters (number of params depends of the service)
	Events: 
		* onError(message): An exception is raised
		* onSuccess(response): Response in json format
		
	
*/
(function($){$.ZoomWebServices=function(serviceurl,settings){var config={service:"",data:{}};if(settings){$.extend(config,settings)}$.ZoomWebServices.raiseError=function(a){if(settings.onError!==undefined){settings.onError(a)}};$.ajax({url:serviceurl+"/"+settings.service,type:"POST",crossDomain: true,data:settings.data,dataType:"jsonp",error:function(xhr,ajaxOptions,thrownError){if(xhr.status=="404"){$.ZoomWebServices.raiseError("404: URL not found");return false}if(xhr.status=="500"){var myObj=eval("("+xhr.responseText+")");$.ZoomWebServices.raiseError(myObj.message);return false}$.ZoomWebServices.raiseError("Unknown Exception: "+xhr.status)},success:function(a){if(settings.onSuccess!==undefined){settings.onSuccess(a)}}});return this}})(jQuery)