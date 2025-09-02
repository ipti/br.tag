<?php
<ul class="t-menu">
    <?php if (TagUtils::checkAccess(['admin', 'reader', 'manager'])): ?>
        <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=school") ? 'active' : '' ?>">
            <?php
            if (count(Yii::app()->user->usersSchools) == 1) {
                $schoolurl = Yii::app()->createUrl('school/update', array('id' => Yii::app()->user->school));
            }
            ?>
            <a class="t-menu-item__link" href="<?php echo $schoolurl ?>">
                <span class="t-icon-school t-menu-item__icon"></span>
                <span class="t-menu-item__text">Escola</span>
            </a>
        </li>
        <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classroom") ? 'active' : '' ?>">
            <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('classroom') ?>">
                <span class="t-icon-people t-menu-item__icon"></span>
                <span class="t-menu-item__text">Turmas</span>
            </a>
        </li>
        <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=student") ? 'active' : '' ?>">
            <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('student') ?>">
                <span class="t-icon-pencil t-menu-item__icon"></span>
                <span class="t-menu-item__text">Alunos</span>
            </a>
        </li>
        <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=instructor") ? 'active' : '' ?>">
            <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('instructor') ?>">
                <span class="t-icon-book t-menu-item__icon"></span>
                <span class="t-menu-item__text">Professores</span>
            </a>
        </li>
        <li
            class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=calendar") ? 'active' : '' ?> hide-responsive">
            <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('calendar') ?> ">
                <span class="t-icon-calendar t-menu-item__icon"></span>
                <span class="t-menu-item__text">Calendário Escolar</span>
            </a>
        </li>
        <li
            class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=curricularmatrix") ? 'active' : '' ?> hide-responsive">
            <a class="t-menu-item__link"
                href="<?php echo Yii::app()->createUrl('curricularmatrix') ?> ">
                <span class="t-icon-line_graph t-menu-item__icon"></span>
                <span class="t-menu-item__text">Matriz Curricular</span>
            </a>
        </li>
        <li
            class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=timesheet") ? 'active' : '' ?> hide-responsive">
            <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('timesheet') ?> ">
                <span class="t-icon-blackboard t-menu-item__icon"></span>
                <span class="t-menu-item__text">Quadro de Horário</span>
            </a>
        </li>
    <?php endif ?>


    <?php if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)): ?>
        <li
            class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=calendar") ? 'active' : '' ?> hide-responsive">
            <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('calendar') ?> ">
                <span class="t-icon-calendar t-menu-item__icon"></span>
                <span class="t-menu-item__text">Calendário Escolar</span>
            </a>
        </li>
    <?php endif ?>

    <?php if (TagUtils::checkAccess(['admin', 'reader', 'manager'])): ?>
        <li id="menu-electronic-diary" class="t-menu-group <?=
                                                                strpos($_SERVER['REQUEST_URI'], "?r=courseplan") ||
                                                                strpos($_SERVER['REQUEST_URI'], "?r=classes/classContents") ||
                                                                strpos($_SERVER['REQUEST_URI'], "?r=classes/frequency") ||
                                                                strpos($_SERVER['REQUEST_URI'], "?r=grades/grades") ||
                                                                strpos($_SERVER['REQUEST_URI'], "?r=enrollment/reportCard")
                                                                ? 'active' : '' ?>">
            <i class="submenu-icon fa fa-chevron-right"></i>
            <i class="submenu-icon fa fa-chevron-down"></i>
            <a id="menu-electronic-diary-trigger" data-toggle="collapse" class="t-menu-group__link"
                href="#submenu-electronic-diary">
                <span class="t-icon-schedule t-menu-item__icon t-menu-group__icon"></span>
                <span class="t-menu-group__text">Diário Eletrônico</span>
            </a>
            <ul class="collapse <?=
                                strpos($_SERVER['REQUEST_URI'], "?r=courseplan") ||
                                    strpos($_SERVER['REQUEST_URI'], "?r=classes/classContents") ||
                                    strpos($_SERVER['REQUEST_URI'], "?r=classes/frequency") ||
                                    strpos($_SERVER['REQUEST_URI'], "?r=grades/grades") ||
                                    strpos($_SERVER['REQUEST_URI'], "?r=enrollment/reportCard") ||
                                    strpos($_SERVER['REQUEST_URI'], "?r=enrollment/gradesRelease") ||
                                    strpos($_SERVER['REQUEST_URI'], "?r=aeerecord") ? 'in' : '' ?>"
                id="submenu-electronic-diary">

                <li
                    class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=courseplan") ? 'active' : '' ?>">
                    <a class="t-menu-item__link"
                        href="<?php echo Yii::app()->createUrl('courseplan/courseplan') ?>">
                        <span class="t-icon-diary t-menu-item__icon"></span>
                        <span class="t-menu-item__text">Plano de Aula</span>
                    </a>
                </li>
                <?php if (TagUtils::checkAccess(['admin', 'reader', 'manager'])): ?>
                    <li
                        class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classes/classContents") ? 'active' : '' ?>">
                        <a class="t-menu-item__link"
                            href="<?php echo Yii::app()->createUrl('classes/classContents') ?>">
                            <span class="t-icon-topics t-menu-item__icon"></span>
                            <span class="t-menu-item__text">Aulas Ministradas</span>
                        </a>
                    </li>
                    <li
                        class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classes/frequency") ? 'active' : '' ?>">
                        <a class="t-menu-item__link"
                            href="<?php echo Yii::app()->createUrl('classes/frequency') ?>">
                            <span class="t-icon-checklist t-menu-item__icon"></span>
                            <span class="t-menu-item__text">Frequência</span>
                        </a>
                    </li>
                <?php endif ?>
                <?php if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id) && (Yii::app()->features->isEnable("FEAT_FREQ_CLASSCONT"))): ?>
                    <li
                        class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classes/classContents") ? 'active' : '' ?>">
                        <a class="t-menu-item__link"
                            href="<?php echo Yii::app()->createUrl('classes/classContents') ?>">
                            <span class="t-icon-topics t-menu-item__icon"></span>
                            <span class="t-menu-item__text">Aulas Ministradas</span>
                        </a>
                    </li>
                    <li
                        class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classes/frequency") ? 'active' : '' ?>">
                        <a class="t-menu-item__link"
                            href="<?php echo Yii::app()->createUrl('classes/frequency') ?>">
                            <span class="t-icon-checklist t-menu-item__icon"></span>
                            <span class="t-menu-item__text">Frequência</span>
                        </a>
                    </li>
                <?php endif ?>
                <?php if (Yii::app()->features->isEnable("FEAT_GRADES")): ?>
                    <li
                        class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=grades/grades") ? 'active' : '' ?>">
                        <a class="t-menu-item__link"
                            href="<?php echo Yii::app()->createUrl('grades/grades') ?> ">
                            <span class="t-icon-edition t-menu-item__icon"></span>
                            <span class="t-menu-item__text">Notas</span>
                        </a>
                    </li>
                <?php endif ?>
                <?php if (Yii::app()->features->isEnable("FEAT_REPORTCARD")): ?>
                    <li
                        class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=enrollment/reportCard") ? 'active' : '' ?>">
                        <a class="t-menu-item__link"
                            href="<?php echo Yii::app()->createUrl('enrollment/reportCard') ?> ">
                            <span class="t-report_card t-menu-item__icon"></span>
                            <span class="t-menu-item__text">Lançamento de Notas</span>
                        </a>
                    </li>
                <?php endif ?>
                <?php if (Yii::app()->features->isEnable("FEAT_GRADESRELEASE")): ?>
                    <li
                        class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=enrollment/gradesRelease") ? 'active' : '' ?>">
                        <a class="t-menu-item__link"
                            href="<?php echo Yii::app()->createUrl('enrollment/gradesRelease') ?> ">
                            <span class="t-report_card t-menu-item__icon"></span>
                            <span class="t-menu-item__text">Lançamento de Notas</span>
                        </a>
                    </li>
                <?php endif ?>
                <?php if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)): ?>
                    <li
                        class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=aeerecord") ? 'active' : '' ?>">
                        <a class="t-menu-item__link"
                            href="<?php echo Yii::app()->createUrl('aeerecord/default/') ?> ">
                            <span class="t-icon-copy t-menu-item__icon"></span>
                            <span class="t-menu-item__text">Ficha AEE</span>
                        </a>
                    </li>
                <?php endif ?>
                <?php if (TagUtils::checkAccess(['admin', 'reader', 'manager'])): ?>
                    <li
                        class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=aeerecord") ? 'active' : '' ?>">
                        <a class="t-menu-item__link"
                            href="<?php echo Yii::app()->createUrl('aeerecord/default/admin') ?> ">
                            <span class="t-icon-copy t-menu-item__icon"></span>
                            <span class="t-menu-item__text">Ficha AEE</span>
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        <?php endif ?>
        </li>
        <?php if (Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id)): ?>
            <li
                class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classes/validateClassContents") ? 'active' : '' ?>">
                <a class="t-menu-item__link"
                    href="<?php echo Yii::app()->createUrl('classes/validateClassContents') ?>">
                    <span class="t-icon-topics t-menu-item__icon"></span>
                    <span class="t-menu-item__text">Aulas Ministradas</span>
                </a>
            </li>
        <?php endif ?>
        <?php if (Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id)): ?>
            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=courseplan") ? 'active' : '' ?>">
                <a class="t-menu-item__link"
                    href="<?php echo Yii::app()->createUrl('courseplan/courseplan') ?>">
                    <span class="t-icon-diary t-menu-item__icon"></span>
                    <span class="t-menu-item__text">Plano de Aula</span>
                </a>
            </li>
        <?php endif ?>
        <?php if (Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id)): ?>
            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=aeerecord") ? 'active' : '' ?>">
                <a class="t-menu-item__link"
                    href="<?php echo Yii::app()->createUrl('aeerecord/default/admin') ?> ">
                    <span class="t-icon-copy t-menu-item__icon"></span>
                    <span class="t-menu-item__text">Ficha AEE</span>
                </a>
            </li>
        <?php endif ?>
        <?php if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)): ?>
            <li
                class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classdiary/default/") ? 'active' : '' ?>">
                <a class="t-menu-item__link"
                    href="<?php echo Yii::app()->createUrl('classdiary/default/') ?> ">
                    <span class="t-classdiary t-menu-item__icon"></span>
                    <span class="t-menu-item__text">Diario de Classe</span>
                </a>
            </li>
        <?php endif ?>
        <?php if (
            Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) || Yii::app()->getAuthManager()->checkAccess('manager', Yii::app()->user->loginInfos->id)
            || Yii::app()->getAuthManager()->checkAccess('reader', Yii::app()->user->loginInfos->id)
        ): ?>
            <li
                class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=reports") ? 'active' : '' ?> hide-responsive">
                <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('reports') ?>">
                    <span class="t-icon-column_graphi t-menu-item__icon"></span>
                    <span class="t-menu-item__text">Relatórios</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) || Yii::app()->getAuthManager()->checkAccess('manager', Yii::app()->user->loginInfos->id) || Yii::app()->getAuthManager()->checkAccess('reader', Yii::app()->user->loginInfos->id)): ?>
            <li id="menu-quiz"
                class="t-menu-item  <?= strpos($_SERVER['REQUEST_URI'], "?r=quiz") ? 'active' : '' ?> hide-responsive">
                <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('quiz') ?>">
                    <span class="t-icon-question-group t-menu-item__icon"></span>
                    <span class="t-menu-item__text">Questionário</span>
                </a>
            </li>
            <?php if (Yii::app()->features->isEnable("FEAT_FOOD")): ?>
                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=foods") ? 'active' : '' ?>">
                    <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('foods') ?> ">
                        <span class="t-icon-apple t-menu-item__icon"></span>
                        <span class="t-menu-item__text">Merenda Escolar</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=lunch") ? 'active' : '' ?>">
                    <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('lunch') ?> ">
                        <span class="t-icon-apple t-menu-item__icon"></span>
                        <span class="t-menu-item__text">Merenda Escolar</span>
                    </a>
                </li>
            <?php endif; ?>
            <li id="menu-integrations" class="t-menu-group <?=
                                                            strpos($_SERVER['REQUEST_URI'], "?r=censo/index") ||
                                                                strpos($_SERVER['REQUEST_URI'], "?r=sagres") ||
                                                                strpos($_SERVER['REQUEST_URI'], "?r=gestaopresente") ||
                                                                strpos($_SERVER['REQUEST_URI'], "?r=sedsp")
                                                                ? 'active' : '' ?>">
                                                                <i class="submenu-icon fa fa-chevron-right"></i>
                                                                <iclass="submenu-icon fa fa-chevron-down"></i>
                <a id="menu-integrations-trigger" data-toggle="collapse" class="t-menu-group__link"
                    href="#submenu-integrations">
                    <span class="t-icon-integration t-menu-item__icon t-menu-group__icon"></span>
                    <span class="t-menu-group__text">Integrações</span>
                </a>
                <ul class="collapse <?=
                                    strpos($_SERVER['REQUEST_URI'], "?r=censo/index") ||
                                        strpos($_SERVER['REQUEST_URI'], "?r=sagres") ||
                                        strpos($_SERVER['REQUEST_URI'], "?r=gestaopresente") ||
                                        strpos($_SERVER['REQUEST_URI'], "?r=sedsp") ? 'in' : '' ?>"
                    id="submenu-integrations">
                    <li
                        class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=censo/index") ? 'active' : '' ?>">
                        <a class="t-menu-item__link"
                            href="<?php echo Yii::app()->createUrl('censo/index') ?> ">
                            <span class="t-icon-educacenso t-menu-item__icon"></span>
                            <span class="t-menu-item__text">Educacenso</span>
                        </a>
                    </li>
                    <?php if (INSTANCE != "BUZIOS") { ?>
                        <li
                            class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=sagres") ? 'active' : '' ?>">
                            <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('sagres') ?> ">
                                <span class="t-icon-sagres t-menu-item__icon"></span>
                                <span class="t-menu-item__text">Sagres</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (Yii::app()->features->isEnable("FEAT_SEDSP")) { ?>
                        <li
                            class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=sedsp") ? 'active' : '' ?>">
                            <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('sedsp') ?>">
                                <span class="t-icon-sp  t-menu-item__icon"></span>
                                <span class="t-menu-item__text">SEDSP</span>
                            </a>
                        </li>
                    <?php } ?>
                    <li
                        class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], '?r=gestaopresente') ? 'active' : '' ?>">
                        <a class="t-menu-item__link"
                            href="<?php echo Yii::app()->createUrl('gestaopresente') ?> ">
                            <span class="t-icon-educacenso t-menu-item__icon"></span>
                            <span class="t-menu-item__text">Gestão Presente</span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif ?>
        <li
            class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=admin/editPassword") ? 'active' : '' ?> hide-responsive">
            <a class="t-menu-item__link"
                href="<?php echo Yii::app()->createUrl('admin/editPassword', array("id" => Yii::app()->user->loginInfos->id)) ?>">
                <span class="t-icon-lock t-menu-item__icon"></span>
                <span class="t-menu-item__text">Alterar senha</span>
            </a>
        </li>
        <?php if (Yii::app()->getAuthManager()->checkAccess('nutritionist', Yii::app()->user->loginInfos->id)): ?>
            <?php if (Yii::app()->features->isEnable("FEAT_FOOD")): ?>
                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=foods") ? 'active' : '' ?>">
                    <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('foods') ?> ">
                        <span class="t-icon-apple t-menu-item__icon"></span>
                        <span class="t-menu-item__text">Merenda Escolar</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=lunch") ? 'active' : '' ?>">
                    <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('lunch') ?> ">
                        <span class="t-icon-apple t-menu-item__icon"></span>
                        <span class="t-menu-item__text">Merenda Escolar</span>
                    </a>
                </li>
        <?php endif;
        endif;
        ?>
        <?php if (
            Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)
            || Yii::app()->getAuthManager()->checkAccess('reader', Yii::app()->user->loginInfos->id)
        ) { ?>
            <li
                class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=admin") ? 'active' : '' ?> hide-responsive">
                <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('admin') ?>">
                    <span class="t-icon-configuration-adm t-menu-item__icon"></span>
                    <span class="t-menu-item__text">Administração</span>
                </a>
            </li>
            <?php if (Yii::app()->features->isEnable("FEAT_DASHBOARD_POWER")): ?>
                <li class="t-menu-item hide-responsive">
                    <a class="t-menu-item__link" href="<?php echo Yii::app()->createUrl('dashboard') ?>">
                        <span class="t-icon-bar_graph t-menu-item__icon"></span>
                        <span class="t-menu-item__text">Gestão de Resultados</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="t-menu-item hide-responsive">
                    <a class="t-menu-item__link"
                        href="<?php echo Yii::app()->createUrl('resultsmanagement') ?>">
                        <span class="t-icon-bar_graph t-menu-item__icon"></span>
                        <span class="t-menu-item__text">Gestão de Resultados</span>
                    </a>
                </li>
            <?php endif; ?>
        <?php } ?>
</ul>