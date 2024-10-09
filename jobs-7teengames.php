<?php
/**
 * Plugin Name: Plugin de vagas 7TEEN GAMES
 * Description: Plugin para gerenciar e exibir vagas do time da 7TEEN GAMES.
 * Version: 1.0
 * Author: Kim Freitas
 * Text Domain: jobs-7teengames
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function jobs_7teengames_register_post_type() {
    $labels = array(
        'name'                  => _x( 'Vagas', 'Post Type General Name', 'jobs-7teengames' ),
        'singular_name'         => _x( 'Vaga', 'Post Type Singular Name', 'jobs-7teengames' ),
        'menu_name'             => __( 'Vagas', 'jobs-7teengames' ),
        'name_admin_bar'        => __( 'Vaga', 'jobs-7teengames' ),
        'archives'              => __( 'Arquivo de Vagas', 'jobs-7teengames' ),
        'attributes'            => __( 'Atributos da Vaga', 'jobs-7teengames' ),
        'parent_item_colon'     => __( 'Vaga Parente:', 'jobs-7teengames' ),
        'all_items'             => __( 'Todas as Vagas', 'jobs-7teengames' ),
        'add_new_item'          => __( 'Adicionar Nova Vaga', 'jobs-7teengames' ),
        'add_new'               => __( 'Adicionar Nova', 'jobs-7teengames' ),
        'new_item'              => __( 'Nova Vaga', 'jobs-7teengames' ),
        'edit_item'             => __( 'Editar Vaga', 'jobs-7teengames' ),
        'update_item'           => __( 'Atualizar Vaga', 'jobs-7teengames' ),
        'view_item'             => __( 'Ver Vaga', 'jobs-7teengames' ),
        'view_items'            => __( 'Ver Vagas', 'jobs-7teengames' ),
        'search_items'          => __( 'Buscar Vagas', 'jobs-7teengames' ),
        'not_found'             => __( 'Nenhuma vaga encontrada', 'jobs-7teengames' ),
        'not_found_in_trash'    => __( 'Nenhuma vaga encontrada na lixeira', 'jobs-7teengames' ),
        'featured_image'        => __( 'Imagem Destacada', 'jobs-7teengames' ),
        'set_featured_image'    => __( 'Definir imagem destacada', 'jobs-7teengames' ),
        'remove_featured_image' => __( 'Remover imagem destacada', 'jobs-7teengames' ),
        'use_featured_image'    => __( 'Usar como imagem destacada', 'jobs-7teengames' ),
        'insert_into_item'      => __( 'Inserir na vaga', 'jobs-7teengames' ),
        'uploaded_to_this_item' => __( 'Carregado para esta vaga', 'jobs-7teengames' ),
        'items_list'            => __( 'Lista de vagas', 'jobs-7teengames' ),
        'items_list_navigation' => __( 'Navegação da lista de vagas', 'jobs-7teengames' ),
        'filter_items_list'     => __( 'Filtrar lista de vagas', 'jobs-7teengames' ),
    );
    $args = array(
        'label'                 => __( 'Vaga', 'jobs-7teengames' ),
        'description'           => __( 'Post type para gerenciar vagas de emprego', 'jobs-7teengames' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies'            => array( 'category' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-businessman',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type( 'vagas', $args );
}

add_action( 'init', 'jobs_7teengames_register_post_type', 0 );

function jobs_7teengames_display_job_title( $content ) {
    if ( is_singular( 'vagas' ) ) {
        $title = '<h1 class="job-title">' . get_the_title() . '</h1>';
        return $title . $content;
    }

    return $content;
}

add_filter( 'the_content', 'jobs_7teengames_display_job_title' );

function jobs_7teengames_display_job_form( $content ) {
    if ( is_singular( 'vagas' ) ) {
        global $post;

        $custom_questions = get_post_meta( $post->ID, '_custom_questions', true );
        $questions = !empty( $custom_questions ) ? explode( "\n", $custom_questions ) : [];

        $status_message = '';
        if ( isset( $_SESSION['job_application_status'] ) ) {
            if ( $_SESSION['job_application_status'] === 'success' ) {
                $status_message = '<div id="application-status-message" class="alert alert-success">Obrigado! Sua candidatura foi enviada com sucesso.</div>';
            } elseif ( $_SESSION['job_application_status'] === 'error' ) {
                $status_message = '<div id="application-status-message" class="alert alert-danger">Ocorreu um erro ao enviar sua candidatura: ' . esc_html( $_SESSION['job_application_error'] ) . '</div>';
            }

            unset( $_SESSION['job_application_status'] );
            unset( $_SESSION['job_application_error'] );
        }

        $form = '<h2>Candidate-se:</h2>';
        $form .= '<form method="post" action="" enctype="multipart/form-data">';
        $form .= '<p><label for="candidate_name">Nome:</label><br />';
        $form .= '<input type="text" id="candidate_name" name="candidate_name" required></p>';
        $form .= '<p><label for="candidate_email">Email:</label><br />';
        $form .= '<input type="email" id="candidate_email" name="candidate_email" required></p>';
        $form .= '<p><label for="candidate_phone">Telefone</label><br />';
        $form .= '<input type="number" id="candidate_phone" name="candidate_phone" required></p>';
        $form .= '<p><label for="candidate_cv">Currículo (PDF):</label><br />';
        $form .= '<input type="file" id="candidate_cv" name="candidate_cv" accept=".pdf" required></p>';

        if ( !empty( $questions ) ) {
            foreach ( $questions as $index => $question ) {
                $form .= '<p><label for="custom_question_' . $index . '">' . esc_html( trim( $question ) ) . ':</label><br />';
                $form .= '<input type="text" id="custom_question_' . $index . '" name="custom_questions[' . $index . ']" required></p>';
            }
        }

        $form .= '<p><input type="submit" name="submit_job_application" value="Enviar Candidatura" class="custom-submit-button"></p>';
        $form .= '</form>';

        return $status_message . '<div class="job-container">' .
               '<div class="job-content">' . $content . '</div>' .
               '<div class="job-form">' . $form . '</div>' .
               '</div>';
    }

    return $content;
}
add_filter( 'the_content', 'jobs_7teengames_display_job_form' );

function jobs_7teengames_handle_form_submission() {
    if ( isset( $_POST['submit_job_application'] ) && isset( $_FILES['candidate_cv'] ) ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'candidaturas';

        if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
            $_SESSION['job_application_status'] = 'error';
            $_SESSION['job_application_error'] = 'Tabela de candidaturas não encontrada.';
            wp_redirect( get_permalink( get_the_ID() ) );
            exit;
        }

        $name    = sanitize_text_field( $_POST['candidate_name'] );
        $email   = sanitize_text_field( $_POST['candidate_email'] );
        $phone   = sanitize_text_field( $_POST['candidate_phone'] );
        $vaga_id = get_the_ID();

        $custom_questions = get_post_meta( $vaga_id, '_custom_questions', true );
        $questions = !empty( $custom_questions ) ? explode( "\n", $custom_questions ) : [];

        $custom_answers = '';
        if ( isset( $_POST['custom_questions'] ) && is_array( $_POST['custom_questions'] ) ) {
            foreach ( $_POST['custom_questions'] as $index => $answer ) {
                if ( isset( $questions[$index] ) ) {
                    $question = sanitize_text_field( $questions[$index] );
                    $answer = sanitize_text_field( $answer );
                    $custom_answers .= $question . ': ' . $answer . "\n";
                }
            }
        }

        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }

        $uploadedfile = $_FILES['candidate_cv'];
        $upload_overrides = array( 'test_form' => false, 'mimes' => array( 'pdf' => 'application/pdf' ) );
        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

        if ( $movefile && ! isset( $movefile['error'] ) ) {
            $cv_url = $movefile['url'];

            $inserted = $wpdb->insert(
                $table_name,
                array(
                    'nome' => $name,
                    'email' => $email,
                    'telefone' => $phone,
                    'curriculo_url' => $cv_url,
                    'vaga_id' => $vaga_id,
                    'perguntas_respostas' => $custom_answers,
                    'data_envio' => current_time( 'mysql' )
                ),
                array( '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
            );

            if ( $inserted ) {
                $admin_email = get_option( 'jobs_7teengames_email', get_option('admin_email') );
                $subject     = 'Nova candidatura para a vaga: ' . get_the_title();
                $body        = "Nome: $name\nEmail: $email\nTelefone: $phone\n\nO currículo foi anexado como PDF:\n$cv_url\n\n";
                $body       .= "Respostas às perguntas:\n" . $custom_answers;
                $headers     = array( 'Content-Type: text/plain; charset=UTF-8' );

                wp_mail( $admin_email, $subject, $body, $headers );

                $_SESSION['job_application_status'] = 'success';
                wp_redirect( get_permalink( $vaga_id ) );
                exit;
            } else {
                error_log( 'Erro ao inserir no banco de dados: ' . $wpdb->last_error );

                $_SESSION['job_application_status'] = 'error';
                $_SESSION['job_application_error'] = 'Erro ao salvar a candidatura no banco de dados: ' . $wpdb->last_error;
                wp_redirect( get_permalink( $vaga_id ) );
                exit;
            }

        } else {
            $_SESSION['job_application_status'] = 'error';
            $_SESSION['job_application_error'] = $movefile['error'];
            wp_redirect( get_permalink( $vaga_id ) );
            exit;
        }
    }
}
add_action( 'wp', 'jobs_7teengames_handle_form_submission' );

function jobs_7teengames_enqueue_styles() {
    wp_enqueue_style( 'jobs-7teengames-styles', plugin_dir_url( __FILE__ ) . 'style.css' );
}

add_action( 'wp_enqueue_scripts', 'jobs_7teengames_enqueue_styles' );

function jobs_7teengames_add_custom_metabox() {
    add_meta_box(
        'custom_questions_metabox',
        'Perguntas Customizadas',
        'jobs_7teengames_render_metabox',
        'vagas',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'jobs_7teengames_add_custom_metabox' );

function jobs_7teengames_render_metabox( $post ) {
    $custom_questions = get_post_meta( $post->ID, '_custom_questions', true );
    $questions = !empty( $custom_questions ) ? explode( "\n", $custom_questions ) : [];

    echo '<div id="custom-questions-container">';
    
    if (!empty($questions)) {
        foreach ($questions as $index => $question) {
            echo '<div class="custom-question">';
            echo '<input type="text" name="custom_questions[]" value="' . esc_attr($question) . '" placeholder="Digite a pergunta..." style="width:80%;" />';
            echo '<button type="button" class="button remove-question">Remover</button>';
            echo '</div>';
        }
    }
    
    echo '</div>';
    
    echo '<button type="button" id="add-question" class="button">Adicionar Pergunta</button>';
    
    echo '
    <script>
    jQuery(document).ready(function($) {
        $("#add-question").on("click", function() {
            var questionField = `<div class="custom-question">
                                    <input type="text" name="custom_questions[]" placeholder="Digite a pergunta..." style="width:80%;" />
                                    <button type="button" class="button remove-question">Remover</button>
                                </div>`;
            $("#custom-questions-container").append(questionField);
        });
        
        $(document).on("click", ".remove-question", function() {
            $(this).parent().remove();
        });
    });
    </script>';
}

function jobs_7teengames_save_custom_questions( $post_id ) {
    if ( isset( $_POST['custom_questions'] ) && is_array( $_POST['custom_questions'] ) ) {
        $custom_questions = implode( "\n", array_map( 'sanitize_text_field', $_POST['custom_questions'] ) );
        update_post_meta( $post_id, '_custom_questions', $custom_questions );
    }
}
add_action( 'save_post', 'jobs_7teengames_save_custom_questions' );

function jobs_7teengames_add_settings_page() {
    add_submenu_page(
        'edit.php?post_type=vagas',
        'Configurações de Candidaturas',
        'Configurações',
        'manage_options',
        'jobs-7teengames-settings',
        'jobs_7teengames_render_settings_page'
    );
}
add_action( 'admin_menu', 'jobs_7teengames_add_settings_page' );

function jobs_7teengames_render_settings_page() {
    if ( isset( $_POST['jobs_7teengames_email'] ) ) {
        update_option( 'jobs_7teengames_email', sanitize_email( $_POST['jobs_7teengames_email'] ) );
        echo '<div id="message" class="updated notice is-dismissible"><p>Configurações salvas.</p></div>';
    }

    $email = get_option( 'jobs_7teengames_email', get_option( 'admin_email' ) );

    echo '<div class="wrap">';
    echo '<h1>Configurações de Candidaturas</h1>';
    echo '<form method="post" action="">';
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th scope="row"><label for="jobs_7teengames_email">E-mail para receber candidaturas</label></th>';
    echo '<td><input type="email" id="jobs_7teengames_email" name="jobs_7teengames_email" value="' . esc_attr( $email ) . '" class="regular-text"></td>';
    echo '</tr>';
    echo '</table>';
    echo '<p class="submit"><input type="submit" class="button-primary" value="Salvar alterações"></p>';
    echo '</form>';
    echo '</div>';
}

function jobs_7teengames_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'candidaturas';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nome varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        telefone varchar(20) NOT NULL,
        curriculo_url varchar(255) NOT NULL,
        vaga_id mediumint(9) NOT NULL,
        perguntas_respostas text NOT NULL,
        data_envio datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta( $sql );

    if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
        error_log( "Erro: A tabela $table_name não foi criada corretamente." );
    } else {
        error_log( "Sucesso: A tabela $table_name foi criada ou atualizada." );
    }
}

register_activation_hook( __FILE__, 'jobs_7teengames_create_table' );

function jobs_7teengames_add_submenu() {
    add_submenu_page(
        'edit.php?post_type=vagas',
        'Candidaturas',
        'Candidaturas',
        'manage_options',
        'jobs-7teengames-candidaturas',
        'jobs_7teengames_render_candidaturas_page'
    );
}
add_action( 'admin_menu', 'jobs_7teengames_add_submenu' );

function jobs_7teengames_render_candidaturas_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'candidaturas';

    if ( isset( $_POST['delete_selected'] ) && isset( $_POST['selected_candidaturas'] ) ) {
        $selected_ids = $_POST['selected_candidaturas'];
        foreach ( $selected_ids as $id ) {
            $id = intval( $id );
            $wpdb->delete( $table_name, array( 'id' => $id ), array( '%d' ) );
        }
        echo '<div class="notice notice-success is-dismissible"><p>Candidaturas deletadas com sucesso.</p></div>';
    }

    $results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY data_envio DESC" );

    echo '<div class="wrap">';
    echo '<h1>Candidaturas Recebidas</h1>';

    echo '<form method="post" action="">';

    echo '<div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">';
    echo '<input type="submit" name="delete_selected" value="Deletar Selecionados" class="button button-danger" style="background-color: red; color: white; border-color: red;">';
    echo '</div>';

    echo '<table class="widefat fixed" cellspacing="0">';
    echo '<thead><tr><th><input type="checkbox" id="select_all"></th><th>Nome</th><th>Email</th><th>Telefone</th><th>Currículo</th><th>Perguntas Customizáveis</th><th>Data de Envio</th></tr></thead>';
    echo '<tbody>';

    foreach ( $results as $row ) {
        echo '<tr>';
        echo '<td><input type="checkbox" name="selected_candidaturas[]" value="' . esc_attr( $row->id ) . '"></td>';
        echo '<td>' . esc_html( $row->nome ) . '</td>';
        echo '<td>' . esc_html( $row->email ) . '</td>';
        echo '<td>' . esc_html( $row->telefone ) . '</td>';
        echo '<td><a href="' . esc_url( $row->curriculo_url ) . '" target="_blank">Download</a></td>';

        if ( ! empty( $row->perguntas_respostas ) ) {
            echo '<td><button class="button view-questions" data-questions="' . esc_attr( $row->perguntas_respostas ) . '">Ver Perguntas</button></td>';
        } else {
            echo '<td>—</td>';
        }
        
        echo '<td>' . esc_html( $row->data_envio ) . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

    echo '</form>';
    echo '</div>';

    echo '<div id="questionsModal" style="display:none;">';
    echo '<div class="modal-content">';
    echo '<span id="closeModal" class="close">&times;</span>';
    echo '<div id="questionsContent"></div>';
    echo '</div>';
    echo '</div>';
}

function jobs_7teengames_enqueue_admin_scripts() {
    ?>
    <style>
        #questionsModal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.view-questions').on('click', function() {
                var questionsData = $(this).data('questions');

                var questionsArray = questionsData.split("\n");

                var formattedQuestions = '';
                $.each(questionsArray, function(index, question) {
                    if (question.trim() !== '') {
                        formattedQuestions += '<p>' + question + '</p>';
                    }
                });

                $('#questionsContent').html(formattedQuestions);

                $('#questionsModal').fadeIn();
            });

            $('#closeModal').on('click', function() {
                $('#questionsModal').fadeOut();
            });

            $(window).on('click', function(event) {
                if (event.target.id === 'questionsModal') {
                    $('#questionsModal').fadeOut();
                }
            });

            $('#select_all').on('click', function() {
                $('input[type="checkbox"]').prop('checked', this.checked);
            });
        });
    </script>
    <?php
}
add_action( 'admin_footer', 'jobs_7teengames_enqueue_admin_scripts' );

function jobs_7teengames_start_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'jobs_7teengames_start_session');

function jobs_7teengames_enqueue_styles_and_scripts() {
    wp_enqueue_style( 'jobs-7teengames-styles', plugin_dir_url( __FILE__ ) . 'style.css' );

    wp_add_inline_script( 'jquery', '
        jQuery(document).ready(function($) {
            setTimeout(function() {
                $("#application-status-message").fadeOut("slow");
            }, 10000);
        });
    ' );
}
add_action( 'wp_enqueue_scripts', 'jobs_7teengames_enqueue_styles_and_scripts' );

add_shortcode('listar_vagas_pt', function() {
    return jobs_7teengames_list_vagas_template('Nenhuma vaga encontrada no momento.', 'Ver detalhes', 'Categorias:', 'Todas as Categorias', 'Sem categoria', 'Pesquisar vagas...');
});

add_shortcode('listar_vagas_en', function() {
    return jobs_7teengames_list_vagas_template('No jobs available at the moment.', 'See details', 'Categories:', 'All Categories', 'Uncategorized', 'Search for jobs...');
});

add_shortcode('listar_vagas_es', function() {
    return jobs_7teengames_list_vagas_template('No hay vacantes disponibles en este momento.', 'Ver detalles', 'Todas las categorías', 'Categorías:', 'Sin categoría', 'Búsqueda de vacantes...');
});

function jobs_7teengames_list_vagas_template($no_vacancy_message, $see_details_text, $categories_label, $all_categories, $no_category_label, $vaga_search) {
    if (function_exists('pll_unregister_string')) {
        remove_filter('terms_clauses', 'wpml_terms_clauses');
    }

    $output = '<div class="input-container" style="display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; padding: 16px; gap: 10px;">';
    
    $output .= '<input type="text" id="vaga_search_input" placeholder="' . esc_html($vaga_search) . '" style="padding: 10px; width: 100%; max-width: 600px; box-sizing: border-box; border-radius: 12px;">';

    $categorias = get_terms(array(
        'taxonomy' => 'category',
        'hide_empty' => true,
        'lang' => '',
    ));

    if (!empty($categorias) && !is_wp_error($categorias)) {
        $output .= '<select id="category_filter" style="padding: 10px; border-radius: 12px; max-width: 250px; cursor: pointer;">';
        $output .= '<option value="">' . esc_html($all_categories) . '</option>';
        foreach ($categorias as $categoria) {
            $output .= '<option value="' . esc_attr($categoria->slug) . '">' . esc_html($categoria->name) . '</option>';
        }
        $output .= '</select>';
    }

    $output .= '</div>';

    $args = array(
        'post_type' => 'vagas',
        'posts_per_page' => -1
    );

    $vagas = new WP_Query($args);

    if ($vagas->have_posts()) {
        $output .= '<div id="vaga-list">';

        // Loop pelas vagas
        while ($vagas->have_posts()) {
            $vagas->the_post();
            $vaga_title = get_the_title();
            $vaga_link = get_permalink();

            $vaga_categorias = get_the_terms(get_the_ID(), 'category');
            $categoria_list = '';
            $categoria_slugs = '';
            if (!empty($vaga_categorias) && !is_wp_error($vaga_categorias)) {
                foreach ($vaga_categorias as $categoria) {
                    $categoria_list .= '<span class="vaga-categoria">' . esc_html($categoria->name) . '</span>, ';
                    $categoria_slugs .= esc_attr($categoria->slug) . ' ';
                }
                $categoria_list = rtrim($categoria_list, ', ');
            } else {
                $categoria_list = $no_category_label;
            }

            $output .= '<div class="vaga-item" data-categories="' . esc_attr(trim($categoria_slugs)) . '">';
            $output .= '<div class="vaga-detalhes">';
            $output .= '<h2 class="vaga-title" style="color: #007CC6;">' . esc_html($vaga_title) . '</h2>';
            $output .= '<p class="vaga-categorias">' . esc_html($categories_label) . ' ' . $categoria_list . '</p>';
            $output .= '</div>';
            $output .= '<div class="vaga-botao">';
            $output .= '<a href="' . esc_url($vaga_link) . '" style="padding: 10px 20px; background-color: #007CC6; color: white; text-decoration: none; border-radius: 5px;">' . esc_html($see_details_text) . '</a>';
            $output .= '</div>';
            $output .= '</div>';
        }

        $output .= '</div>';
    } else {
        $output .= '<p>' . esc_html($no_vacancy_message) . '</p>';
    }

    wp_reset_postdata();

    $output .= '<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var input = document.getElementById("vaga_search_input");
            var select = document.getElementById("category_filter");
            var vagas = document.querySelectorAll(".vaga-item");

            function filterVagas() {
                var filter = input.value.toLowerCase();
                var categoryFilter = select.value;

                vagas.forEach(function(vaga) {
                    var title = vaga.querySelector(".vaga-title").textContent.toLowerCase();
                    var categories = vaga.getAttribute("data-categories").toLowerCase();

                    if (title.indexOf(filter) > -1 && (categoryFilter === "" || categories.indexOf(categoryFilter) > -1)) {
                        vaga.style.display = "";
                    } else {
                        vaga.style.display = "none";
                    }
                });
            }

            input.addEventListener("input", filterVagas);
            select.addEventListener("change", filterVagas);
        });
    </script>';

    if (function_exists('pll_unregister_string')) {
        add_filter('terms_clauses', 'wpml_terms_clauses');
    }

    return $output;
}
