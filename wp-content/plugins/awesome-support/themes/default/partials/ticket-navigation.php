<div class="wpas-ticket-buttons-top">
    <?php wpas_make_button(__('My Tickets', 'awesome-support'),
                           ['type' => 'link', 'link' => wpas_get_tickets_list_page_url(), 'class' => 'wpas-btn wpas-btn-default wpas-link-ticketlist']); ?>
    <?php wpas_make_button(__('Open a ticket', 'awesome-support'),
                           ['type' => 'link', 'link' => wpas_get_submission_page_url(), 'class' => 'wpas-btn wpas-btn-default wpas-link-ticketnew']); ?>
    <?php wpas_make_button(__('Logout', 'awesome-support'),
                           ['type' => 'link', 'link' => wp_logout_url(), 'class' => 'wpas-btn wpas-btn-default wpas-link-logout']); ?>
</div>