import React from "react";

import Alert from "@material-ui/lab/Alert";
import { useMutation } from "react-query";
import { useHistory } from "react-router";
import { requestUpdateRegistration } from "../../query/classroom";


export const Controller = () => {
    const history = useHistory()
    const requestUpdateRegistrationMutation = useMutation(
        ({data, id}) => requestUpdateRegistration(data, id),
        {
            onError: (error) => {
                console.log(error.response.data.message);
            },
            onSuccess: (data) => {
                history.goBack()
                return (
                    <Alert>
                        opoaaa
                    </Alert>

                )


            },
        }
    );
    return { requestUpdateRegistrationMutation }
} 