import Alert from "@material-ui/lab/Alert";
import { useMutation } from "react-query";
import { useHistory } from "react-router";
import { requestSaveRegistration } from "../../query/registration";
import { useAlert } from 'react-alert'


export const Controller = () => {
    const history = useHistory()
    const alert = useAlert()
    const requestSaveRegistrationMutation = useMutation(
        (data) => requestSaveRegistration(data),
        {
          onError: (error) => {
            alert.show(error.response.data.message)
            console.log(error.response.data.message);
          },
          onSuccess: (data) => {
            history.push('/')
          },
        }
      );
    return {requestSaveRegistrationMutation}
} 