<?php

/*
 * ARQUIVO GERADO AUTOMATICAMENTE
 * ================================
 * Gerado por: scripts/generate-routes.php
 * Comando:    composer run routes:generate
 *
 * NÃO EDITE ESTE ARQUIVO MANUALMENTE.
 * Qualquer alteração será sobrescrita na próxima geração.
 *
 * Para adicionar ou renomear rotas, altere os controllers correspondentes
 * e re-execute: composer run routes:generate -- quiz
 */

class QuizRoutes
{
    // DefaultController
    public const DEFAULT_ANSWER = 'quiz/default/answer';
    public const DEFAULT_CREATEGROUP = 'quiz/default/createGroup';
    public const DEFAULT_CREATEOPTION = 'quiz/default/createOption';
    public const DEFAULT_CREATEQUESTION = 'quiz/default/createQuestion';
    public const DEFAULT_CREATEQUESTIONGROUP = 'quiz/default/createQuestionGroup';
    public const DEFAULT_CREATEQUIZ = 'quiz/default/createQuiz';
    public const DEFAULT_DELETEGROUP = 'quiz/default/deleteGroup';
    public const DEFAULT_DELETEOPTION = 'quiz/default/deleteOption';
    public const DEFAULT_DELETEQUESTION = 'quiz/default/deleteQuestion';
    public const DEFAULT_DELETEQUESTIONGROUP = 'quiz/default/deleteQuestionGroup';
    public const DEFAULT_DELETEQUIZ = 'quiz/default/deleteQuiz';
    public const DEFAULT_GROUP = 'quiz/default/group';
    public const DEFAULT_INDEX = 'quiz/default/index';
    public const DEFAULT_QUESTION = 'quiz/default/question';
    public const DEFAULT_QUESTIONGROUP = 'quiz/default/questionGroup';
    public const DEFAULT_QUIZ = 'quiz/default/quiz';
    public const DEFAULT_SETQUIZQUESTION = 'quiz/default/setQuizQuestion';
    public const DEFAULT_UNSETQUIZQUESTION = 'quiz/default/unsetQuizQuestion';
    public const DEFAULT_UPDATEGROUP = 'quiz/default/updateGroup';
    public const DEFAULT_UPDATEOPTION = 'quiz/default/updateOption';
    public const DEFAULT_UPDATEQUESTION = 'quiz/default/updateQuestion';
    public const DEFAULT_UPDATEQUESTIONGROUP = 'quiz/default/updateQuestionGroup';
    public const DEFAULT_UPDATEQUIZ = 'quiz/default/updateQuiz';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
