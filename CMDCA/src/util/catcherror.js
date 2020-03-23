import Swal from 'sweetalert2'
import withReactContent from 'sweetalert2-react-content'

const alert= withReactContent(Swal); 
export default function catcherror(error){
    try{
        switch(error.response.status){
            case 401:
                alert.fire({
                    icon: 'error',
                    title: 'Sessão expirada!',
                    text: 'Sua sessão foi expirada, você será redirecionado para a tela de login!',
                    onClose: ()=>{
                        localStorage.clear();
                        window.history.replaceState(null,"login","/");
                        window.history.go();
                    },
                });
                break;
            case 400:
                alert.fire({
                    icon: 'error',
                    title: 'Erro na requisição!',
                    text: error.response.data.error,
                });
                break;
            default:
                alert.fire({
                    icon: 'error',
                    title: 'Erro interno!',
                    text: 'Um erro ocorreu no servidor, a operação não pôde ser executada!',
                });
        }
    }catch(error){
        alert.fire({
            icon: 'error',
            title: 'Erro de sistema!',
            text: 'Um erro ocorreu no servidor, tente novamente mais tarde!',
            onClose: ()=>{
                localStorage.clear();
                window.history.replaceState(null,"login","/");
                window.history.go();
            }
        });
    }
}