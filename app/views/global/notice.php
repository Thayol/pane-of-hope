<script>
    function dismissNotification(element)
    {
        element.parentElement.style.display = 'none';
    }
</script>
<?php

function render_notice($type, $message)
{
    echo "<div class=\"notice notice-{$type}\">{$message}<a href=\"#\" onclick=\"dismissNotification(this)\" class=\"notice-dismiss\">x</a></div>";
}

if (!empty($notice_error))
{
    render_notice("error", $notice_error);
}
if (!empty($notice_success))
{
    render_notice("success", $notice_success);
}
if (!empty($notice_neutral))
{
    render_notice("neutral", $notice_neutral);
}
