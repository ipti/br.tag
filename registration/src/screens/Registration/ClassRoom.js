import { FormControl, FormLabel } from "@material-ui/core";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import React, { useContext } from "react";
import Select from "react-select";
import homeImg from "../../assets/images/illustration-home.png";
import { ButtonPurple } from "../../components/Buttons";
import { useFetchRequestSchoolStages } from "../../query/registration";
import RegistrationContext from '../../containers/Registration/context';
import styles from "./styles";

const useStyles = makeStyles(styles);

const customStyles = {
    control: base => ({
        ...base,
        height: "60px",
        minHeight: "60px",
        fontFamily: "Roboto, Helvetica, Arial, sans-serif"
    }),
    menu: base => ({
        ...base,
        fontFamily: "Roboto, Helvetica, Arial, sans-serif"
    })
};
const Classroom = props => {
    const classes = useStyles();

    const date = Date(Date.now());
    const { idSchool, setIdStage, setIdStagevsmodality } = useContext(RegistrationContext);

    const { data } = useFetchRequestSchoolStages({id: idSchool});

    console.log(data)
  
    const onButton = () => {
        props.nextStep('2')
    }
    return (
        <>
            <Grid
                className={classes.contentStart}
                container
                direction="row"
                justifyContent="center"
                alignItems="center"
            >
                <Grid item xs={12}>
                    <img src={homeImg} alt="" />
                </Grid>
                <Grid item xs={12}>
                    <h1>Matrícula Online</h1>
                    <p>
                        Escolha a turma <br /> e clique no botão
                        abaixo
                    </p>
                </Grid>
                <Grid item xs={12}>
                    <FormControl
                        component="fieldset"
                        className={classes.formControl}
                    >
                        <FormLabel>Turma *</FormLabel>
                        <Select
                            styles={customStyles}
                            className="basic-single"
                            classNamePrefix="select"
                            placeholder="Selecione a Turma"
                            options={data}
                            onChange={selectedOption => {
                                setIdStagevsmodality(selectedOption.edcenso_stage_vs_modality.id)
                                setIdStage(selectedOption.edcenso_stage_vs_modality.stage)
                            }}
                        getOptionValue={opt => opt.edcenso_stage_vs_modality.name}
                        getOptionLabel={opt => opt.edcenso_stage_vs_modality.name}
                        />
                    </FormControl>
                </Grid>
            </Grid>
            <Grid
                className={`${classes.marginTop}`}
                container
                direction="row"
                justifyContent="center"
                alignItems="center"
            >
                <Grid item xs={6}>
                    <ButtonPurple
                        type="button"
                        onClick={onButton}
                        title="Continuar"
                    />
                </Grid>
            </Grid>
        </>
    );
};

export default Classroom;