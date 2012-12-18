/* Copyright (C) YOOtheme GmbH, http://www.gnu.org/licenses/gpl.html GNU/GPL */

(function(b){var g=function(){};b.extend(g.prototype,{name:"dropdownMenu",options:{mode:"default",itemSelector:"li",firstLevelSelector:"li.level1",dropdownSelector:"ul",duration:600,remainTime:800,remainClass:"remain",matchHeight:!0,transition:"easeOutExpo",withopacity:!0,centerDropdown:!1,reverseAnimation:!1,fixWidth:!1,fancy:null,flipOn:b(window)},initialize:function(g,j){this.options=b.extend({},this.options,j);var a=this,i=null,q=!1;this.menu=g;this.dropdowns=[];this.options.withopacity=b.support.opacity?
!1:this.options.withopacity;if(this.options.fixWidth){var r=5;this.menu.children().each(function(){r+=b(this).width()});this.menu.css("width",r)}this.options.matchHeight&&this.matchHeight();this.menu.find(this.options.firstLevelSelector).each(function(p){var k=b(this),f=k.find(a.options.dropdownSelector).css({overflow:"hidden"}),g=!1;if(f.length){f.css("overflow","hidden").show();var d=b("<div>").css({overflow:"hidden"}).append("<div></div>");f.hide();var h=d.find("div:first");f.children().appendTo(h);
d.appendTo(f);a.dropdowns.push({dropdown:f,div:d,innerdiv:h})}else g=!0;k.bind({mouseenter:function(){g||(f.show(),d.data("dpwidth",parseFloat(f.width())).data("dpheight",parseFloat(f.height())),f.hide(),h.css({"min-width":d.data("dpwidth"),"min-height":d.data("dpheight")}),a.options.centerDropdown&&f.css("margin-left",-1*(parseFloat(f.css("width"))/2-k.width()/2)),g=!0);q=!0;a.menu.trigger("menu:enter",[k,p]);if(i){if(i.index==p)return;i.item.removeClass(a.options.remainClass);i.div.hide().parent().hide()}if(f.length){f.removeClass("flip");
k.addClass(a.options.remainClass);d.stop().show();f.show();var e=d.data("dpwidth"),b=d.data("dpheight"),c=d.offset(),m=0;try{m=a.options.flipOn.offset().left}catch(j){}(0>c.left||c.left+e-m>a.options.flipOn.width())&&f.addClass("flip");switch(a.options.mode){case "showhide":e={width:e,height:b};d.css(e);break;case "diagonal":c={width:0,height:0};e={width:e,height:b};a.options.withopacity&&(c.opacity=0,e.opacity=1);d.css(c).animate(e,a.options.duration,a.options.transition);break;case "height":c={width:e,
height:0};e={height:b};a.options.withopacity&&(c.opacity=0,e.opacity=1);d.css(c).animate(e,a.options.duration,a.options.transition);break;case "width":c={width:0,height:b};e={width:e};a.options.withopacity&&(c.opacity=0,e.opacity=1);d.css(c).animate(e,a.options.duration,a.options.transition);break;case "slide":f.css({width:e,height:b});d.css({width:e,height:b,"margin-top":-1*b}).animate({"margin-top":0},a.options.duration,a.options.transition);break;default:c={width:e,height:b},e={},a.options.withopacity&&
(c.opacity=0,e.opacity=1),d.css(c).animate(e,a.options.duration,a.options.transition)}i={item:k,div:d,index:p}}else i=active=null},mouseleave:function(e){if(e.srcElement&&b(e.srcElement).hasClass("module"))return!1;q=!1;f.length?window.setTimeout(function(){if(!(q||"none"==d.css("display"))){a.menu.trigger("menu:leave",[k,p]);var b=function(){k.removeClass(a.options.remainClass);i=null;d.hide().parent().hide()};if(a.options.reverseAnimation)switch(a.options.mode){case "showhide":b();break;case "diagonal":var c=
{width:0,height:0};a.options.withopacity&&(c.opacity=0);d.stop().animate(c,a.options.duration,a.options.transition,function(){b()});break;case "height":c={height:0};a.options.withopacity&&(c.opacity=0);d.stop().animate(c,a.options.duration,a.options.transition,function(){b()});break;case "width":c={width:0};a.options.withopacity&&(c.opacity=0);d.stop().animate(c,a.options.duration,a.options.transition,function(){b()});break;case "slide":d.stop().animate({"margin-top":-1*parseFloat(d.data("dpheight"))},
a.options.duration,a.options.transition,function(){b()});break;default:c={},a.options.withopacity&&(c.opacity=0),d.stop().animate(c,a.options.duration,a.options.transition,function(){b()})}else b()}},a.options.remainTime):a.menu.trigger("menu:leave")}})});if(this.options.fancy){var h=b.extend({mode:"move",transition:"easeOutExpo",duration:500,onEnter:null,onLeave:null},this.options.fancy),l=this.menu.append('<div class="fancy bg1"><div class="fancy-1"><div class="fancy-2"><div class="fancy-3"></div></div></div></div>').find(".fancy:first").hide(),
o=this.menu.find(".active:first"),n=null,s=function(a,b){if(!b||!(n&&a.get(0)==n.get(0)))l.stop().show().css("visibility","visible"),"move"==h.mode?!o.length&&!b?l.hide():l.animate({left:a.position().left+"px",width:a.width()+"px"},h.duration,h.transition):b?l.css({opacity:o?0:1,left:a.position().left+"px",width:a.width()+"px"}).animate({opacity:1},h.duration):l.animate({opacity:0},h.duration),n=b?a:null};this.menu.bind({"menu:enter":function(a,b,f){s(b,!0);if(h.onEnter)h.onEnter(b,f,l)},"menu:leave":function(a,
b,f){s(o,!1);if(h.onLeave)h.onLeave(b,f,l)},"menu:fixfancy":function(){n&&l.stop().show().css({left:n.position().left+"px",width:n.width()+"px"})}});o.length&&"move"==h.mode&&s(o,!0)}},matchHeight:function(){this.menu.find("li.level1.parent").each(function(){var g=0;b(this).find("ul.level2").each(function(){g=Math.max(b(this).height(),g)}).css("min-height",g)})}});b.fn[g.prototype.name]=function(){var m=arguments,j=m[0]?m[0]:null;return this.each(function(){var a=b(this);if(g.prototype[j]&&a.data(g.prototype.name)&&
"initialize"!=j)a.data(g.prototype.name)[j].apply(a.data(g.prototype.name),Array.prototype.slice.call(m,1));else if(!j||b.isPlainObject(j)){var i=new g;g.prototype.initialize&&i.initialize.apply(i,b.merge([a],m));a.data(g.prototype.name,i)}else b.error("Method "+j+" does not exist on jQuery."+g.name)})}})(jQuery);