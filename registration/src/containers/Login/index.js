import React, { useState } from "react";
import * as Yup from "yup";
import Login from "../../screens/Login/Login";
import api from "../../services/api";
import { login } from "../../services/auth";
import { useHistory } from "react-router-dom";

const SignIn = () => {
  const [isValid, setValid] = useState(true);
  let history = useHistory();
  const onSubmit = values => {
    api.post("login", values).then(function(response) {
      if (response && !("error" in response.data)) {
        login(response.data.data.access_token);
        history.push("/inicio");
      } else {
        setValid(false);
      }
    });
  };

  const validationSchema = Yup.object().shape({
    username: Yup.string().required("Username é obrigatório!"),
    password: Yup.string()
      .min(6, "Senha deve ter no mínimo 6 caracteres")
      .required("Senha é obrigatória!")
  });

  let initialValues = {
    email: "",
    password: ""
  };

  return (
    <Login
      initialValues={initialValues}
      validationSchema={validationSchema}
      onSubmit={onSubmit}
      isValid={isValid}
    />
  );
};

export default SignIn;
