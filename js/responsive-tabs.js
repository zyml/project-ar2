/**
 * Easy Responsive Tabs Plugin
 * Author: Samson.Onna <samson3d@gmail.com>
 * Modified for use in Project AR2 by zyml <zy@zy.sg>
 */
(function ($) {
	$.fn.extend({
		easyResponsiveTabs: function (options) {
			//Set the default values, use comma to separate the settings, example:
			var defaults = {
				type: 'default', //default, vertical, accordion;
				width: 'auto',
				fit: true
			};
			//Variables
			var options = $.extend(defaults, options);       
			var opt = options, jtype = opt.type, jfit = opt.fit, jwidth = opt.width, vtabs = 'vertical', accord = 'accordion';

			//Main function
			this.each(function () {
				var $respTabs = $(this);
				$respTabs.find('ul.tabs li').addClass('resp-tab-item');
				$respTabs.css({
					'display': 'block',
					'width': jwidth
				});

				$respTabs.find('.tabs-container > div').addClass('resp-tab-content');
				jtab_options();
				//Properties Function
				function jtab_options() {
					if (jtype == vtabs) {
						$respTabs.addClass('resp-vtabs');
					}
					if (jfit === true) {
						$respTabs.css({ width: '100%', margin: '0px' });
					}
					if (jtype == accord) {
						$respTabs.addClass('resp-easy-accordion');
						$respTabs.find('.resp-tabs-list').css('display', 'none');
					}
				}

				//Assigning the h2 markup
				var $tabItemh2;
				$respTabs.find('.resp-tab-content').before("<h2 class='resp-accordion' role='tab'><span class='resp-arrow'></span></h2>");

				var itemCount = 0;
				$respTabs.find('.resp-accordion').each(function () {
					$tabItemh2 = $(this);
					var innertext = $respTabs.find('.resp-tab-item:eq(' + itemCount + ')').text();
					$respTabs.find('.resp-accordion:eq(' + itemCount + ')').append(innertext);
					$tabItemh2.attr('aria-controls', 'tab_item-' + (itemCount));
					itemCount++;
				});

				//Assigning the 'aria-controls' to Tab items
				var count = 0,
					$tabContent;
				$respTabs.find('.resp-tab-item').each(function () {
					$tabItem = $(this);
					$tabItem.attr('aria-controls', 'tab_item-' + (count));
					$tabItem.attr('role', 'tab');

					//First active tab                   
					$respTabs.find('.resp-tab-item').first().addClass('ui-state-active');
					$respTabs.find('.resp-accordion').first().addClass('ui-state-active');
					$respTabs.find('.resp-tab-content').first().addClass('resp-tab-content-active').attr('style', 'display:block');

					//Assigning the 'aria-labelledby' attr to tab-content
					var tabcount = 0;
					$respTabs.find('.resp-tab-content').each(function () {
						$tabContent = $(this);
						$tabContent.attr('aria-labelledby', 'tab_item-' + (tabcount));
						tabcount++;
					});
					count++;
				});

				//Tab Click action function
				$respTabs.find("[role=tab]").each(function () {
					var $currentTab = $(this);
					$currentTab.click(function () {

						var $tabAria = $currentTab.attr('aria-controls');

						if ($currentTab.hasClass('resp-accordion') && $currentTab.hasClass('ui-state-active')) {
							$respTabs.find('.resp-tab-content-active').slideUp('', function () { $(this).addClass('resp-accordion-closed'); });
							$currentTab.removeClass('ui-state-active');
							return false;
						}
						if (!$currentTab.hasClass('ui-state-active') && $currentTab.hasClass('resp-accordion')) {
							$respTabs.find('.ui-state-active').removeClass('ui-state-active');
							$respTabs.find('.resp-tab-content-active').slideUp().removeClass('resp-tab-content-active resp-accordion-closed');
							$respTabs.find("[aria-controls=" + $tabAria + "]").addClass('ui-state-active');

							$respTabs.find('.resp-tab-content[aria-labelledby = ' + $tabAria + ']').slideDown().addClass('resp-tab-content-active');
						} else {
							$respTabs.find('.ui-state-active').removeClass('ui-state-active');
							$respTabs.find('.resp-tab-content-active').removeAttr('style').removeClass('resp-tab-content-active').removeClass('resp-accordion-closed');
							$respTabs.find("[aria-controls=" + $tabAria + "]").addClass('ui-state-active');
							$respTabs.find('.resp-tab-content[aria-labelledby = ' + $tabAria + ']').addClass('resp-tab-content-active').attr('style', 'display:block');
						}

						return false;
					});
					//Window resize function                   
					$(window).resize(function () {
						$respTabs.find('.resp-accordion-closed').removeAttr('style');
					});
				});
			});
		}
	});
})(jQuery);
