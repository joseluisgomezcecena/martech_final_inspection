<?php

class Login extends CI_Controller
{



    public function do_login()
    {

        $user_email = $this->input->get('user_email');
        $user_name =  $this->input->get('user_name');
        $user_lastname = $this->input->get('user_lastname');

        $user_level_name = $this->input->get('user_level_name');

        $martech_number = $this->input->get('user_martech_number');
        $department_name = $this->input->get('user_department_name');
        $department_id = $this->input->get('user_department_id');

        if (
            isset($user_email)
            && isset($user_name)
            && isset($user_lastname)
            && isset($martech_number)
            && isset($department_name)
            && isset($department_id)
            && isset($user_level_name)
        ) {

            if ($department_name == 'Quality' && $department_id == '3') {
                //Si el departament es de calidad entonces si se puede logear
                $this->session->set_userdata(IS_LOGGED_IN, TRUE);
                $this->session->set_userdata(USER_TYPE, QUALITY_USER);
                $this->session->set_userdata(EMAIL, $user_email);
                $this->session->set_userdata(NAME, $user_name);
                $this->session->set_userdata(LASTNAME, $user_lastname);
                $this->session->set_userdata(MARTECH_NUMBER, $martech_number);
                $this->session->set_userdata(DEPARTMENT_NAME, $department_name);
                $this->session->set_userdata(DEPARTMENT_ID, $department_id);
                $this->session->set_userdata(LEVEL_NAME, $user_level_name);

                redirect($this->input->get('from'));
            } else {
                //No pertenece al equipo de calidad....send message
                $data['error_message'] = "Este usuario no forma parte del equipo de Calidad según nuestros registros.";
                $data['plantas'] = $this->db->get('plantas')->result_array();
                $this->load->view('pages/intro', $data);
            }
        } else {
            //Not All data retrieved...
            $data['error_message'] = "No hay datos suficientes para darle autorización al Sistema.";
            $data['plantas'] = $this->db->get('plantas')->result_array();


            $this->load->view('pages/intro', $data);
        }
    }

    public function logout()
    {
        session_destroy();
        redirect('/');
    }
}
