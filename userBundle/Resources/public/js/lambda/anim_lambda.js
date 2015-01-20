$(function () {

	   $('.wrapper_el_projets').click(function(){
			$('.wrapper_el_projets').css({"border" : "", "border-radius": "", "box-shadow" : " 1px 1px 2px 2px #fff" });
			$(this).css({"border" : " 1px solid #ccc", "border-radius": " 5px", "box-shadow" : " 1px 1px 2px 2px #ccc" });
			$('.projet_el_liste_bas').css({"display" : "none"});
			$(this).children('.expandlink').children('.projet_el_liste_bas').css('display','block');
    });
	
	
	 $("#defprofil>form>div").hide();
	
    $('#fos_choix_type_membre').change(function(){
	 $("#defprofil>form>div").fadeOut(100);
        if ($('#fos_choix_type_membre').val() == 0){ 
            $("#young").fadeIn(100);           
        }
		if ($('#fos_choix_type_membre').val() == 1){ 
            $("#instM").fadeIn(100);           
        }
	   if ($('#fos_choix_type_membre').val() == 2){ 
            $("#assoM").fadeIn(100);           
        }
		
    })
	
 $('#assoM_associations').data("placeholder","Rechercher...").chosen({width: "60%"});
 $('#young_institutions').data("placeholder","Rechercher...").chosen({width: "60%"});
 $('#instM_institutions').data("placeholder","Rechercher...").chosen({width: "60%"});
});
