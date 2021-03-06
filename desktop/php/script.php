<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'jquery.fileTree/jqueryFileTree', 'css');
include_file('3rdparty', 'codemirror/lib/codemirror', 'js');
include_file('3rdparty', 'codemirror/lib/codemirror', 'css');
include_file('3rdparty', 'codemirror/addon/edit/matchbrackets', 'js');
include_file('3rdparty', 'codemirror/mode/htmlmixed/htmlmixed', 'js');
include_file('3rdparty', 'codemirror/mode/clike/clike', 'js');
include_file('3rdparty', 'codemirror/mode/php/php', 'js');
include_file('3rdparty', 'codemirror/mode/shell/shell', 'js');
include_file('3rdparty', 'codemirror/mode/python/python', 'js');
include_file('3rdparty', 'codemirror/mode/ruby/ruby', 'js');
include_file('3rdparty', 'codemirror/mode/perl/perl', 'js');

sendVarToJS('eqType', 'script');
sendVarToJS('userScriptDir', getRootPath() . '/' . config::byKey('userScriptDir', 'script'));
$eqLogics = eqLogic::byType('script');
?>
<style>
    .CodeMirror-scroll {height: 100%; overflow-y: auto; overflow-x: auto;}
</style>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default btn-sm tooltips" id="bt_getFromMarket" title="Récupérer du market" style="width : 100%"><i class="fa fa-shopping-cart"></i> {{Market}}</a>

                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un script}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend>{{Mes scripts}}
        </legend>
        <?php
        if (count($eqLogics) == 0) {
            echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore de script. Cliquez sur Ajouter un script pour commencer}}</span></center>";
        } else {
            ?>
            <div class="eqLogicThumbnailContainer">
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
                    echo "<center>";
                    echo '<img src="plugins/script/doc/images/script_icon.png" height="105" width="95" />';
                    echo "</center>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                    echo '</div>';
                }
                ?>
            </div>
        <?php } ?>
    </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
        <form class="form-horizontal">
            <fieldset>
                <legend>
                    <i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}
                    <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i>
                    <a class="btn btn-xs btn-default pull-right eqLogicAction" data-action="copy"><i class="fa fa-files-o"></i> {{Dupliquer}}</a>
                </legend>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{Nom de l'équipement script}}</label>
                    <div class="col-sm-3">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement script}}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                    <div class="col-sm-3">
                        <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                            <option value="">{{Aucun}}</option>
                            <?php
                            foreach (object::all() as $object) {
                                echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{Catégorie}}</label>
                    <div class="col-sm-8">
                        <?php
                        foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                            echo '<label class="checkbox-inline">';
                            echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                            echo '</label>';
                        }
                        ?>

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-1">
                        <label class="checkbox-inline">
                            <input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/> Activer 
                        </label>
                    </div>
                    <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-1">
                        <label class="checkbox-inline">
                            <input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/> Visible 
                        </label>
                    </div>
                </div>
                <div class="form-group expertModeVisible">
                    <label class="col-sm-3 control-label">{{Auto-actualisation (cron)}}</label>
                    <div class="col-sm-2">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="autorefresh" placeholder="{{Auto-actualisation (cron)}}"/>
                    </div>
                    <div class="col-sm-1">
                        <i class="fa fa-question-circle cursor bt_pageHelp floatright" data-name="cronSyntaxe"></i>
                    </div>
                </div>
            </fieldset> 
        </form>

        <legend>{{Script}}</legend>
        <a class="btn btn-success btn-sm cmdAction" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une commande script}}</a><br/><br/>
        <div class="alert alert-info">
            {{ Sous type : <br/>
            - Slider : mettre #slider# pour récupérer la valeur<br/>
            - Color : mettre #color# pour récupérer la valeur<br/>
            - Message : mettre #title# et #message#}}
        </div>
        <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th style="width: 250px;">{{Nom}}</th>
                    <th style="width: 100px;">{{Type script}}</th>
                    <th style="width: 70px;">{{Type}}</th>
                    <th>{{Requête}}</th>
                    <th style="width: 250px;">{{Options}}</th>
                    <th style="width: 110px;">{{Divers}}</th>
                    <th style="width: 170px;">{{Paramètres}}</th>
                    <th style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<div id="md_browseScriptFile" title="Parcourir...">
    <div style="display: none;" id="div_browseScriptFileAlert"></div>
    <div id="div_browseScriptFileTree"></div>
</div>

<div id="md_editScriptFile" title="Editer...">
    <div style="display: none;" id="div_editScriptFileAlert"></div>
    <textarea id="ta_editScriptFile" class="form-control" style="height: 100%;"></textarea>
</div>

<?php include_file('3rdparty', 'jquery.fileTree/jquery.easing.1.3', 'js'); ?>
<?php include_file('3rdparty', 'jquery.fileTree/jqueryFileTree', 'js'); ?>
<?php include_file('desktop', 'script', 'js', 'script'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
