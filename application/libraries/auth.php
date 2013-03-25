<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class auth{
   
    function __construct() {
       
    }
   
    /**
     * Función de Logueo de Usuarios.
     * Verifica el usuario y la contraseña. Y en caso de éxito genera la sesión
     * y devuelve TRUE. En caso de falla devuelve FALSE.
     *
     * @param string $nombre_usuario
     * @param string $contrasena
     * @return boolean
     */
    function login($nombre_usuario, $contrasena)
    {
        //Recupero la instancia del controlador
        $CI =& get_instance();
       
        //Armo array para consulta
        $data = array(
            "nombreUsuario" => $nombre_usuario,
            "contrasena" => md5($contrasena)
        );
       
        //Ejecuto consulta a BBDD
        $query = $CI->db->get_where("Usuarios", $data);
       
        //Si el resultado tiene un número distinto de uno entonces el login falló.
        if($query->num_rows() !== 1)
        {
            // Si sale por acá el nombre de usuario y la contraseña
            // no se encontraron en la base de datos
            return false;
        }
        else
        {
            //Si sale por acá el login fue exitoso
            //Seteo al usuario como logueado.
            $data = array(
                   'idUsuario'      =>  $query->row()->idUsuario,
                   'nombreUsuario'  =>  $query->row()->nombreUsuario,
                   'idPersonal'     =>  $query->row()->idPersonal,
                   'logueado'       =>  TRUE
                );
           
            //Recupero los roles del usuario.
            //Construyo la sentencia con ActiveDB
            $CI->db->select('Roles.descripcion');
            $CI->db->from('RolesPorUsuario');
            $CI->db->join('Roles', 'Roles.idRol = RolesPorUsuario.idRol');
            $CI->db->where('idUsuario', $data['idUsuario']);
           
            //Obtengo el resultado
            $query=$CI->db->get();
           
            //Convierto el resultado en array
            $resultado = $query->result_array();
           
            //Genero un array de la forma.
            //$roles[x]="descrición rol"
            $roles=array();
            foreach ($resultado as $rol) {
                $roles[]=$rol['descripcion'];
            }
           
            //Lo agrego al array data y lo inserto en la sesión de usuario.
            $data['roles']=$roles;
            $CI->session->set_userdata($data);
            return true;
        }
    }
   
    /**
     * Función de deslogueo de usuario.
     * Destruye la sesión.
     */
    function logout()
    {  
        //Recupero la instancia del controlador
        $CI =& get_instance();
        //Destruyo la sesión de usuario.
        $CI->session->sess_destroy();
    }
   
    /**
     * Función de comprobación de estado de logueo.
     * Devuelve TRUE o FALSE según en el usuario esté logueado o no.
     *
     * @return boolean
     */
    function logueado()
    {
     $CI =& get_instance();
     return ($CI->session->userdata("logueado")) ? true : false;
    }
   
    /**
     * Función de comprobación de permisos para sectores.
     * Si el usuario tiene un rol asignado == $rol devuelve TRUE
     * sino FALSE.
     *
     * @param string $rol
     * @return boolean
     */
    function chequeoRol($rol){
        $CI =& get_instance();
       
        //Recupero roles de la sesión
        $rolesUsuario = $CI->session->userdata('roles');
       
        //Por cada rol recuperado lo comparo con el solicitado.
        foreach ($rolesUsuario as $rolUsuario) {
            if($rolUsuario == $rol){
                return TRUE;
            }
            //Si es Administrador devuelvo siempre TRUE
            if($rolUsuario == 'Administrador'){
                return TRUE;
            }
        }
        return FALSE;
    }
}
