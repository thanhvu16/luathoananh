'use strict';
/**
 * Get modal popup
 * @type {null}
 */
var $currentModal = null;
/**
 * Get trigger that activates modal
 * @type {null}
 */
var $currentTrigger = null;
/**
 * Get previous Trigger
 * @type {null}
 */
var $previousTrigger = null;
/**
 * count number of modal
 * @type {number}
 */
var numberModal = 0;

/**
 * Get remote
 * @returns {boolean}
 */
function isRemote(){
    return $currentTrigger.attr('href') != undefined && $currentTrigger.attr('href') != '#' && $currentTrigger.attr('href') != '';
}
/**
 * Initiate something
 */
$(document).on("show.bs.modal", function (e)
{
    numberModal ++;
    $currentTrigger = $(e.relatedTarget);
    $currentModal != null && $currentModal.hide();
    $currentModal = e.currentModal;
});
/**
 * Add loading processing so that user can wait under pleasure state
 */
$(document).on("shown.bs.modal", function (e)
{
    isRemote() && $(e.target).removeData("bs.modal").find(".modal-content").empty();
    isRemote() && $($currentTrigger.attr('data-target') + ' .modal-content').addClass("loading");
});
/**
 * Remove loading state after processing successfully
 */
$(document).on("loaded.bs.modal", function (e)
{
    if(isRemote()) {
        (numberModal == 1) && $($currentTrigger.attr('data-target') + ' .modal-content').removeClass("loading");
        $previousTrigger && $($previousTrigger.attr('data-target') + ' .modal-content').removeClass("loading");
        $previousTrigger = $currentTrigger;
        numberModal--;
    }
});
/**
 * Empty content when close popup
 */
$(document).on("hide.bs.modal", function (e)
{
    isRemote() && $(e.target).removeData("bs.modal").find(".modal-content").empty();
});
/**
 * Clear all initial things
 */
$(document).on("hidden.bs.modal", function (e)
{
    if(isRemote()) {
        (numberModal == 1) && $($currentTrigger.attr('data-target') + ' .modal-content').removeClass("loading");
        $previousTrigger && $($previousTrigger.attr('data-target') + ' .modal-content').removeClass("loading");

        $currentModal = null;
        $previousTrigger = null;
    }
});