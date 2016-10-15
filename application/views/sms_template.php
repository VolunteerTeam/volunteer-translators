<?
if(isset($data))
{
    $this->load->view('sms_templates/'.$tpl,$data);
} else {
    $this->load->view('sms_templates/'.$tpl);
}
?>