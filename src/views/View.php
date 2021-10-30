<?php

namespace Views;

/**
 * Class View
 *
 * Main class View,
 * got basics functions for all views
 *
 * @package Views
 */
class View
{

    /**
     * Displays the divider - allows containers to be separated conveniently
     * @author Thomas Cardon
     * @param $data
     * @param $classes
     * @return string - Container HTML data
     */
    public function renderContainerDivider() {
      return '<div class="container-divider"></div>';
    }

    /**
     * Displays a container in an elegant way
     * @author Thomas Cardon
     * @param $data
     * @param $classes
     * @return string - Container HTML data
     */
    public function renderContainer($data, $title = '', $classes = 'container-sm px-4 py-5 my-5 text-center') {
      return '<div class="' . $classes . '">' . ($title == '' ? '' : '<h1 class="display-5 fw-bold" style="color: var(--color-primary-200) !important;">' . $title . '</h1>') . $data . '</div>';
    }

    /**
     * Displays a hero header for a page
     * @author Thomas Cardon
     * @param $title
     * @param $p - The descriptions
     * @param $url - The side image URL
     * @return string - Container HTML data
     */
    public function renderHeroHeader($title = 'Page sans titre', $p, $url = URL_PATH . TV_PLUG_PATH . 'public/img/alert.png') {
      return '
      <section class="container col-xxl-10 py-5">
          <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
            <div class="col-10 col-sm-8 col-lg-6">
              <img draggable="false" src="' . $url . '" class="d-block mx-lg-auto img-fluid" loading="lazy" width="256">
            </div>
            <div class="col-lg-6">
              <h1 class="display-5 fw-bold title-bold">' . $title . '</h1>
              <p class="lead">
                ' . $p . '
              </p>
            </div>
          </div>
        </section>';
    }

    /**
     * Display a table, showing all element from a database
     *
     * @param $name
     * @param $title
     * @param $dataHeader
     * @param $dataList
     * @param string $idTable
     *
     * @return string
     */
    public function displayAll($name, $title, $dataHeader, $dataList, $idTable = '') {
        $name = '\'' . $name . '\'';
        $table = '
    		<h2>' . $title . '</h2>
        <div class="row g-3 align-items-center">
          <div class="col-auto">
            <label for="key' . $idTable . '" class="col-form-label">Rechercher</label>
          </div>
          <div class="col-auto">
            <input class="form-control form-control-sm" type="text" id="key' . $idTable . '" name="key" onkeyup="search(\'' . $idTable . '\')" placeholder="Entrez un mot-clé...">
          </div>
        </div>
    		<form method="post">
    			<div class="table-responsive">
    				<table class="table table-striped table-hover" id="table' . $idTable . '">
    					<thead>
    						<tr class="text-center">
    							<th width="5%" class="text-center" onclick="sortTable(0, \'' . $idTable . '\')">#</th>
    		                    <th scope="col" width="5%" class="text-center"><input type="checkbox" onClick="toggle(this, ' . $name . ')" /></th>';
        $count = 1;
        foreach ($dataHeader as $data) {
            ++$count;
            $table .= '<th scope="col" class="text-center" onclick="sortTable(' . $count . ', \'' . $idTable . '\')">' . $data . '</th>';
        }

        $table .= '</tr></thead><tbody>';

        foreach ($dataList as $data) {
            $table .= '<tr>';
            foreach ($data as $column) {
                $table .= '<td class="text-center">' . $column . '</td>';
            }
            $table .= '</tr>';
        }

        $table .= '</tbody></table></div>';

        $table .= '
        <div class="row d-flex justify-content-center">
          <div class="col-auto my-auto">
            <a type="submit" href="' . home_url('/creer-une-alerte') . '"  class="btn btn-primary" role="button">Créer</a>
            <button type="submit" class="btn btn-danger" name="delete" onclick="return confirm(\' Voulez-vous supprimer le(s) élément(s) sélectionné(s) ?\');">Supprimer</button>
          </div>
          <div class="col-auto my-auto">
            <span id="passwordHelpInline" class="form-text">
              Supprime les éléments sélectionnés.
            </span>
          </div>
        </div>
	    </form>';
        return $table;
    }

    public function pageNumber($pageNumber, $currentPage, $url, $numberElement = null) {
        $pagination = '
        <nav aria-label="Page navigation example">
            <ul class="pagination">';

        if ($currentPage > 1) {
            $pagination .= '
            <li class="page-item">
              <a class="page-link" href="' . $url . '/' . ($currentPage - 1) . '/?number=' . $numberElement . '" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <li class="page-item"><a class="page-link" href="' . $url . '/1/?number=' . $numberElement . '">1</a></li>';
        }
        if ($currentPage > 3) {
            $pagination .= '<li class="page-item page-link disabled">...</li>';
        }
        for ($i = $currentPage - 3; $i < $currentPage; ++$i) {
            if ($i > 1) {
                $pagination .= '<li class="page-item"><a class="page-link" href="' . $url . $i . '/?number=' . $numberElement . '">' . $i . '</a></li>';
            }
        }
        $pagination .= '
        <li class="page-item active_ecran" aria-current="page">
          <a class="page-link" href="' . $url . $currentPage . '/?number=' . $numberElement . '">' . $currentPage . '<span class="sr-only">(current)</span></a>
        </li>';
        for ($i = $currentPage + 1; $i < $currentPage + 3; ++$i) {
            if ($i < $pageNumber) {
                $pagination .= '<li class="page-item"><a class="page-link" href="' . $url . '/' . $i . '/?number=' . $numberElement . '">' . $i . '</a></li>';
            }
        }
        if ($currentPage < $pageNumber) {
            if ($pageNumber - $currentPage > 3) {
                $pagination .= '<li class="page-item page-link disabled">...</li>';
            }
            $pagination .= '
            <li class="page-item"><a class="page-link" href="' . $url . '/' . $pageNumber . '/?number=' . $numberElement . '">' . $pageNumber . '</a></li>
            <li class="page-item">
              <a class="page-link" href="' . $url . '/' . ($currentPage + 1) . '/?number=' . $numberElement . '" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>';
        }
        $pagination .= '
          </ul>
        </nav>';
        return $pagination;
    }

    /**
     * Create a link for modify an element
     *
     * @param $link
     *
     * @return string
     */
    public function buildLinkForModify($link) {
        return '<a href="' . $link . '">Modifier</a>';
    }

    /**
     * Create a checkbox
     *
     * @param $name
     * @param $id
     *
     * @return string
     */
    public function buildCheckbox($name, $id) {
        return '<input type="checkbox" name="checkboxStatus' . $name . '[]" value="' . $id . '"/>';
    }

    /**
     * Create the begin of a multi select
     *
     * @return string
     */
    public function displayStartMultiSelect() {
        return '<ul class="nav nav-pills mb-3" id="nav-tab" role="tablist">';
    }

    /**
     * Create one tab for the multi select
     *
     * @param $id           string id de l'onglet
     * @param $title        string titre de l'onglet
     * @param $active       bool affiche l'onglet (si c'est à true) lors du chargement de la page
     * @return string
     */
    public function displayTitleSelect($id, $title, $active = false) {
        $string = '<li class="nav-item' . ($active ? ' active' : '') . '" role="presentation">';

        $string .= '
          <button class="nav-link' . ($active ? ' active' : '') . '" id="nav-' . $id . '-tab" href="#nav-' . $id . '" role="tab" data-bs-toggle="pill" data-bs-target="#"nav-' . $id . '" aria-controls="nav-' . $id . '" aria-selected="false">' . $title . '</button>
        ';

        $string .= '</li>';
        return $string;
    }

    /**
     * Close the creation of new tab
     *
     * @return string
     */
    public function displayEndOfTitle() {
        return '</ul>
        <br />
        <div class="tab-content" id="nav-tabContent">';
    }

    /**
     * Create the content for one tab
     *
     * @param $id           string
     * @param $content      string
     * @param $active       bool
     *
     * @return string
     */
    public function displayContentSelect($id, $content, $active = false) {
        $string = '<div class="tab-pane fade' . ($active ? ' show active' : '');
        $string .= '" id="nav-' . $id . '" role="tabpanel" aria-labelledby="nav-' . $id . '-tab">';
        $string .= $content;
        $string .= '</div>';

        return $string;
    }

    /**
     * Refresh the page
     */
    public function refreshPage() {
        echo '<meta http-equiv="refresh" content="0">';
    }

    /**
     * @param $title
     * @param $content
     * @param null $redirect
     */
    public function buildModal($title, $content, $redirect = null) {
        $modal = '
		<!-- MODAL -->
		<div class="modal" id="myModal" tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">' . $title . '</h5>
		      </div>
		      <div class="modal-body">
		        ' . $content . '
		      </div>
		      <div class="modal-footer">';
        if (empty($redirect)) {
            $modal .= '<button type="button" class="btn button_ecran" onclick="$(\'#myModal\').hide();">Fermer</button>';
        } else {
            $modal .= '<button type="button" class="btn button_ecran" onclick="document.location.href =\' ' . $redirect . ' \'">Fermer</button>';
        }
        $modal .= '</div>
		    </div>
		  </div>
		</div>

		<script>
			$(\'#myModal\').show();
		</script>';

        echo $modal;
    }

    /**
     * Close a div
     *
     * @return string
     */
    public function displayEndDiv() {
        return '</div>';
    }

    /**
     * Display a message if the two password are different
     */
    public function displayBadPassword() {
        $this->buildModal('Mauvais mot de passe', '<p class=\'alert alert-danger\'>Les deux mots de passe ne sont pas correctes </p>');
    }

    /**
     * Display a message if an user already exist
     *
     * @param $doubles  array de login
     */
    public function displayErrorDouble($doubles) {
        $content = "";
        foreach ($doubles as $double) {
            $content .= '<p class="alert alert-danger">' . $double . ' a rencontré un problème lors de l\'enregistrement, vérifié son login et son email !</p>';
        }
        $this->buildModal('Erreur durant l\'inscription', $content);
    }

    /**
     * Display a message if the inscription is a success
     */
    public function displayInsertValidate() {
        $this->buildModal('Inscription validée', '<p class=\'alert alert-success\'>Votre inscription a été validée.</p>');
    }

    /**
     * Display a message if the extension of the file is wrong
     */
    public function displayWrongExtension() {
        $this->buildModal('Mauvais fichier !', '<p class="alert alert-danger"> Mauvaise extension de fichier !</p>');
    }

    /**
     * Display a message if the file isn't a good file
     */
    public function displayWrongFile() {
        $this->buildModal('Mauvais fichier !', '<p class="alert alert-danger"> Vous utilisez un mauvais fichier excel / ou vous avez changé le nom des colonnes</p>');
    }

    /**
     * Display a message if the modification is a success
     */
    public function displayModificationValidate($redirect = null) {
        $this->buildModal('Modification réussie', '<p class="alert alert-success"> La modification a été appliquée</p>', $redirect);
    }

    /**
     * Display a message if the creation of an user has failed
     */
    public function displayErrorInsertion() {
        $this->buildModal('Erreur lors de l\'inscription', '<p class="alert alert-danger"> Le login ou l\'adresse mail est déjà utilisé(e) </p>');
    }

    public function errorMessageInvalidForm() {
        $this->buildModal('Le formulaire n\'a pas été correctement remplie', '<p class="alert alert-danger">Le formulaire a été mal remplie, veuillez revoir les données rentrées et réessayez.</p>');
    }

    public function errorMessageCantAdd() {
        $this->buildModal('L\'ajout a échoué', '<p class="alert alert-danger">Une erreur s\'est produite lors de l\'envoie du formulaire, veuillez réessayer après avoir vérifié vos informations.</p>');
    }
}
