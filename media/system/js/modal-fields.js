!function(){"use strict";window.processModalParent=function(e,t,d,n,a,o,r){var l=document.getElementById(e+"_id"),m=document.getElementById(e+"_name");return t=t||"",d=d||"",n=n||"",r=r||"",a=a||"",o=o||"",t?(l.value=t,m.value=d,document.getElementById(e+"_select")&&jQuery("#"+e+"_select").addClass("hidden"),document.getElementById(e+"_new")&&jQuery("#"+e+"_new").addClass("hidden"),document.getElementById(e+"_edit")&&jQuery("#"+e+"_edit").removeClass("hidden"),document.getElementById(e+"_clear")&&jQuery("#"+e+"_clear").removeClass("hidden")):(l.value="",m.value=l.getAttribute("data-text"),document.getElementById(e+"_select")&&jQuery("#"+e+"_select").removeClass("hidden"),document.getElementById(e+"_new")&&jQuery("#"+e+"_new").removeClass("hidden"),document.getElementById(e+"_edit")&&jQuery("#"+e+"_edit").addClass("hidden"),document.getElementById(e+"_clear")&&jQuery("#"+e+"_clear").addClass("hidden")),"1"==l.getAttribute("data-required")&&(document.formvalidator.validate(l),document.formvalidator.validate(m)),!1},window.processModalEdit=function(e,t,d,n,a,o,r,l){o=o||n.toLowerCase()+"-form",r=r||"jform_id",l=l||"jform_title";var m=e.parentNode.parentNode.id,i=a;jQuery("#"+m+" iframe").get(0).id="Frame_"+m;var u=jQuery("#Frame_"+m).contents().get(0);return"cancel"===a?(document.getElementById("Frame_"+m).contentWindow.Joomla.submitbutton(n.toLowerCase()+"."+a),jQuery("#"+m).modal("hide")):(jQuery("#Frame_"+m).on("load",function(){u=jQuery(this).contents().get(0),u.getElementById(r)&&"0"!=u.getElementById(r).value&&(window.processModalParent(t,u.getElementById(r).value,u.getElementById(l).value),"save"===a&&window.processModalEdit(e,t,"edit",n,"cancel",o,r,l)),jQuery("#"+m+" iframe").removeClass("hidden")}),u.formvalidator.isValid(u.getElementById(o))&&("save"===a&&(i="apply",jQuery("#"+m+" iframe").addClass("hidden")),document.getElementById("Frame_"+m).contentWindow.Joomla.submitbutton(n.toLowerCase()+"."+i))),!1},window.processModalSelect=function(e,t,d,n,a,o,r,l){return window.processModalParent(t,d,n,a,r,l,o),jQuery("#ModalSelect"+e+"_"+t).modal("hide"),!1}}();
