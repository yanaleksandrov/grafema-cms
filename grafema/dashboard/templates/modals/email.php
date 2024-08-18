<?php
/**
 * Add or update email templates.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/modals/email.php
 *
 * @package     Grafema\Templates
 * @version     2025.1
 */

use Grafema\View;

if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="modal" id="grafema-emails-creator" tabindex="-1" role="dialog" aria-hidden="true" x-cloak>
    <div class="modal__dialog modal__dialog--right modal__dialog--xl" role="document">
        <div class="modal__content" @click.outside="$modal.close()">
            <div class="modal__body modal__body--columns bg-muted-lt">
                <div class="modal__side">
                    <div class="modal__header">
                        <h6 class="modal__title t-muted fw-300">Page ID: <span class="t-dark fw-600">#123</span></h6>
                        <i class="ph ph-copy" x-copy="123"></i>
                        <button type="button" class="modal__close" @click="$modal.close()"></button>
                    </div>
					<?php echo Dashboard\Form::view( 'grafema-emails-creator' ); ?>
                    <div class="modal__footer bg-white">
                        <button type="button" class="btn btn--outline" @click="$modal.close()">Cancel</button>
                        <button type="button" class="btn btn--primary" @click="$dispatch('submit')">Publish</button>
                    </div>
                </div>
                <div class="df jcc">
					<?php
					View::print(
						GRFM_DASHBOARD . 'templates/mails/wrappers.php',
						[
							'body_template' => GRFM_DASHBOARD . 'templates/mails/reset-password.php',
						]
					);
					?>
                </div>
            </div>
        </div>
    </div>
</div>
