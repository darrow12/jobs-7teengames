<?php
/**
 * Plugin Name: Plugin de vagas 7TEEN GAMES
 * Description: Plugin para gerenciar e exibir vagas do time da 7TEEN GAMES.
 * Version: 1.0
 * Author: Kim Freitas
 * Text Domain: jobs-7teengames
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Register Custom Post Type "Vagas"
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

// Register Custom Taxonomy "Setor"
function jobs_7teengames_register_taxonomy() {
    $labels = array(
        'name'              => _x( 'Setores', 'taxonomy general name', 'jobs-7teengames' ),
        'singular_name'     => _x( 'Setor', 'taxonomy singular name', 'jobs-7teengames' ),
        'search_items'      => __( 'Buscar Setores', 'jobs-7teengames' ),
        'all_items'         => __( 'Todos os Setores', 'jobs-7teengames' ),
        'parent_item'       => __( 'Setor Pai', 'jobs-7teengames' ),
        'parent_item_colon' => __( 'Setor Pai:', 'jobs-7teengames' ),
        'edit_item'         => __( 'Editar Setor', 'jobs-7teengames' ),
        'update_item'       => __( 'Atualizar Setor', 'jobs-7teengames' ),
        'add_new_item'      => __( 'Adicionar Novo Setor', 'jobs-7teengames' ),
        'new_item_name'     => __( 'Novo Nome de Setor', 'jobs-7teengames' ),
        'menu_name'         => __( 'Setor', 'jobs-7teengames' ),
    );

    $args = array(
        'hierarchical'      => true, // Como categorias, com hierarquia
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'setor' ),
    );

    register_taxonomy( 'setor', array( 'vagas' ), $args );
}

add_action( 'init', 'jobs_7teengames_register_taxonomy' );

// Add Job Title to Single Job Page
function jobs_7teengames_display_job_title( $content ) {
    if ( is_singular( 'vagas' ) ) {
        $title = '<h1 class="job-title">' . get_the_title() . '</h1>';
        return $title . $content;
    }

    return $content;
}

add_filter( 'the_content', 'jobs_7teengames_display_job_title' );

// Função para exibir o formulário de candidatura na página de uma vaga individual
function jobs_7teengames_display_job_form( $content ) {
    if ( is_singular( 'vagas' ) ) {
        global $post;
        
        // Recupera perguntas customizadas salvas
        $custom_questions = get_post_meta( $post->ID, '_custom_questions', true );
        $questions = !empty( $custom_questions ) ? explode( "\n", $custom_questions ) : [];

        // Cria o formulário HTML
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

        // Adiciona perguntas customizadas ao formulário
        if ( !empty( $questions ) ) {
            foreach ( $questions as $index => $question ) {
                $form .= '<p><label for="custom_question_' . $index . '">' . esc_html( trim( $question ) ) . ':</label><br />';
                $form .= '<input type="text" id="custom_question_' . $index . '" name="custom_questions[' . $index . ']" required></p>';
            }
        }

        $form .= '<p><input type="submit" name="submit_job_application" value="Enviar Candidatura" class="custom-submit-button"></p>';
        $form .= '</form>';

        // Adiciona um contêiner ao redor do conteúdo e do formulário
        return '<div class="job-container">' .
               '<div class="job-content">' . $content . '</div>' .
               '<div class="job-form">' . $form . '</div>' .
               '</div>';
    }

    return $content;
}
add_filter( 'the_content', 'jobs_7teengames_display_job_form' );

// Função para processar o envio do formulário e o upload do arquivo
function jobs_7teengames_handle_form_submission() {
    if ( isset( $_POST['submit_job_application'] ) && isset( $_FILES['candidate_cv'] ) ) {
        // Sanitiza os dados do formulário
        $name    = sanitize_text_field( $_POST['candidate_name'] );
        $email   = sanitize_email( $_POST['candidate_email'] );
        $phone   = sanitize_text_field( $_POST['candidate_phone'] );

        // Processa as respostas das perguntas customizadas, se existirem
        $custom_answers = '';
        if ( isset( $_POST['custom_questions'] ) && is_array( $_POST['custom_questions'] ) ) {
            foreach ( $_POST['custom_questions'] as $index => $answer ) {
                $custom_answers .= 'Pergunta ' . ($index + 1) . ': ' . sanitize_text_field( $answer ) . "\n";
            }
        }

        // Processa o arquivo enviado (currículo em PDF)
        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }

        // Configurações para o upload do arquivo
        $uploadedfile = $_FILES['candidate_cv'];
        $upload_overrides = array( 'test_form' => false, 'mimes' => array( 'pdf' => 'application/pdf' ) );

        // Faz o upload do arquivo
        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

        if ( $movefile && ! isset( $movefile['error'] ) ) {
            // Upload bem-sucedido
            $cv_url = $movefile['url']; // URL do arquivo PDF enviado

            // Configura o e-mail para o administrador do site
            $admin_email = get_option( 'admin_email' );
            $subject     = 'Nova candidatura para a vaga: ' . get_the_title();
            $body        = "Nome: $name\nEmail: $email\nTelefone: $phone\n\nO currículo foi anexado como PDF:\n$cv_url\n\n";
            $body       .= "Respostas às perguntas:\n" . $custom_answers;
            $headers     = array( 'Content-Type: text/plain; charset=UTF-8' );

            // Envia o e-mail
            wp_mail( $admin_email, $subject, $body, $headers );

            // Exibe uma mensagem de sucesso após o envio do formulário
            echo '<p>Obrigado! Sua candidatura foi enviada com sucesso.</p>';
        } else {
            // Tratamento de erros durante o upload
            echo '<p>Ocorreu um erro ao enviar o currículo: ' . $movefile['error'] . '</p>';
        }
    }
}
add_action( 'wp', 'jobs_7teengames_handle_form_submission' );

function jobs_7teengames_enqueue_styles() {
    wp_enqueue_style( 'jobs-7teengames-styles', plugin_dir_url( __FILE__ ) . 'style.css' );
}

add_action( 'wp_enqueue_scripts', 'jobs_7teengames_enqueue_styles' );

// Adiciona a metabox de perguntas customizadas no editor de vagas
function jobs_7teengames_add_custom_metabox() {
    add_meta_box(
        'custom_questions_metabox',       // ID
        'Perguntas Customizadas',         // Título
        'jobs_7teengames_render_metabox', // Callback para exibir o conteúdo
        'vagas',                          // Tipo de post (vagas)
        'normal',                         // Contexto (normal)
        'high'                            // Prioridade
    );
}
add_action( 'add_meta_boxes', 'jobs_7teengames_add_custom_metabox' );

// Renderiza a metabox no editor de vagas
function jobs_7teengames_render_metabox( $post ) {
    // Recuperar perguntas já salvas, se existirem
    $custom_questions = get_post_meta( $post->ID, '_custom_questions', true );

    // Exibir a área para adicionar as perguntas
    echo '<label for="custom_questions">Adicione perguntas customizadas para esta vaga:</label><br />';
    echo '<textarea id="custom_questions" name="custom_questions" rows="5" cols="50" placeholder="Digite uma pergunta por linha...">' . esc_textarea( $custom_questions ) . '</textarea>';
}

// Salva as perguntas customizadas ao salvar a vaga
function jobs_7teengames_save_custom_questions( $post_id ) {
    // Verifica se o campo custom_questions foi enviado
    if ( isset( $_POST['custom_questions'] ) ) {
        // Sanitiza e salva as perguntas no banco de dados como metadados do post
        update_post_meta( $post_id, '_custom_questions', sanitize_textarea_field( $_POST['custom_questions'] ) );
    }
}
add_action( 'save_post', 'jobs_7teengames_save_custom_questions' );