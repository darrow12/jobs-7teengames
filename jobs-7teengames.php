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

        // Mensagem de sucesso ou erro após o envio
        $status_message = '';
        if ( isset( $_SESSION['job_application_status'] ) ) {
            if ( $_SESSION['job_application_status'] === 'success' ) {
                $status_message = '<div id="application-status-message" class="alert alert-success">Obrigado! Sua candidatura foi enviada com sucesso.</div>';
            } elseif ( $_SESSION['job_application_status'] === 'error' ) {
                $status_message = '<div id="application-status-message" class="alert alert-danger">Ocorreu um erro ao enviar sua candidatura: ' . esc_html( $_SESSION['job_application_error'] ) . '</div>';
            }

            // Limpa a sessão após exibir a mensagem
            unset( $_SESSION['job_application_status'] );
            unset( $_SESSION['job_application_error'] );
        }

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
        return $status_message . '<div class="job-container">' .
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
        global $wpdb;
        $table_name = $wpdb->prefix . 'candidaturas'; // Nome da tabela de candidaturas

        // Verifica se a tabela existe
        if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
            // Caso a tabela não exista, exibe um erro
            $_SESSION['job_application_status'] = 'error';
            $_SESSION['job_application_error'] = 'Tabela de candidaturas não encontrada.';
            wp_redirect( get_permalink( get_the_ID() ) );
            exit;
        }

        // Sanitiza os dados do formulário
        $name    = sanitize_text_field( $_POST['candidate_name'] );
        $email   = sanitize_text_field( $_POST['candidate_email'] );  // Usamos sanitize_text_field para aceitar qualquer formato de email
        $phone   = sanitize_text_field( $_POST['candidate_phone'] );
        $vaga_id = get_the_ID(); // ID da vaga atual

        // Recupera as perguntas customizadas salvas no post da vaga
        $custom_questions = get_post_meta( $vaga_id, '_custom_questions', true );
        $questions = !empty( $custom_questions ) ? explode( "\n", $custom_questions ) : [];

        // Processa as respostas das perguntas customizadas
        $custom_answers = '';
        if ( isset( $_POST['custom_questions'] ) && is_array( $_POST['custom_questions'] ) ) {
            foreach ( $_POST['custom_questions'] as $index => $answer ) {
                if ( isset( $questions[$index] ) ) { // Garantimos que a pergunta existe
                    $question = sanitize_text_field( $questions[$index] ); // Recuperamos a pergunta correta
                    $answer = sanitize_text_field( $answer ); // Sanitizamos a resposta
                    $custom_answers .= $question . ': ' . $answer . "\n"; // Formato Pergunta: Resposta
                }
            }
        }

        // Processa o arquivo enviado (currículo em PDF)
        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }

        $uploadedfile = $_FILES['candidate_cv'];
        $upload_overrides = array( 'test_form' => false, 'mimes' => array( 'pdf' => 'application/pdf' ) );
        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

        if ( $movefile && ! isset( $movefile['error'] ) ) {
            // Upload bem-sucedido
            $cv_url = $movefile['url']; // URL do arquivo PDF enviado

            // Salvar candidatura no banco de dados
            $inserted = $wpdb->insert(
                $table_name,
                array(
                    'nome' => $name,
                    'email' => $email,
                    'telefone' => $phone,
                    'curriculo_url' => $cv_url,
                    'vaga_id' => $vaga_id,
                    'perguntas_respostas' => $custom_answers,
                    'data_envio' => current_time( 'mysql' ) // Garante que a data e hora de envio sejam salvas corretamente
                ),
                array( '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
            );

            if ( $inserted ) {
                // Enviar e-mail
                $admin_email = get_option( 'jobs_7teengames_email', 'paulobaronedev@gmail.com' );
                $subject     = 'Nova candidatura para a vaga: ' . get_the_title();
                $body        = "Nome: $name\nEmail: $email\nTelefone: $phone\n\nO currículo foi anexado como PDF:\n$cv_url\n\n";
                $body       .= "Respostas às perguntas:\n" . $custom_answers;
                $headers     = array( 'Content-Type: text/plain; charset=UTF-8' );

                wp_mail( $admin_email, $subject, $body, $headers );

                // Define mensagem de sucesso na sessão e redireciona
                $_SESSION['job_application_status'] = 'success';
                wp_redirect( get_permalink( $vaga_id ) );
                exit;
            } else {
                // Log de erros do WordPress
                error_log( 'Erro ao inserir no banco de dados: ' . $wpdb->last_error );

                // Erro ao inserir no banco de dados
                $_SESSION['job_application_status'] = 'error';
                $_SESSION['job_application_error'] = 'Erro ao salvar a candidatura no banco de dados: ' . $wpdb->last_error;
                wp_redirect( get_permalink( $vaga_id ) );
                exit;
            }

        } else {
            // Define mensagem de erro na sessão e redireciona
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

// Renderiza a metabox no editor de vagas com campos dinâmicos
function jobs_7teengames_render_metabox( $post ) {
    // Recuperar perguntas já salvas, se existirem
    $custom_questions = get_post_meta( $post->ID, '_custom_questions', true );
    $questions = !empty( $custom_questions ) ? explode( "\n", $custom_questions ) : [];

    // Exibir a área para adicionar as perguntas
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
    
    // Adiciona o script para lidar com a adição e remoção de perguntas
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

// Salva as perguntas customizadas ao salvar a vaga
function jobs_7teengames_save_custom_questions( $post_id ) {
    if ( isset( $_POST['custom_questions'] ) && is_array( $_POST['custom_questions'] ) ) {
        // Junta as perguntas em uma string separada por quebras de linha
        $custom_questions = implode( "\n", array_map( 'sanitize_text_field', $_POST['custom_questions'] ) );
        update_post_meta( $post_id, '_custom_questions', $custom_questions );
    }
}
add_action( 'save_post', 'jobs_7teengames_save_custom_questions' );

function jobs_7teengames_add_settings_page() {
    add_submenu_page(
        'edit.php?post_type=vagas', // Página das "Vagas"
        'Configurações de Candidaturas', // Título da página
        'Configurações', // Título do submenu
        'manage_options', // Capacidade necessária
        'jobs-7teengames-settings', // Slug
        'jobs_7teengames_render_settings_page' // Função que renderiza a página
    );
}
add_action( 'admin_menu', 'jobs_7teengames_add_settings_page' );

// Função que renderiza a página de configurações
function jobs_7teengames_render_settings_page() {
    // Verifica se o formulário foi submetido e atualiza a opção
    if ( isset( $_POST['jobs_7teengames_email'] ) ) {
        update_option( 'jobs_7teengames_email', sanitize_email( $_POST['jobs_7teengames_email'] ) );
        echo '<div id="message" class="updated notice is-dismissible"><p>Configurações salvas.</p></div>';
    }

    // Obter o valor atual da opção
    $email = get_option( 'jobs_7teengames_email', get_option( 'admin_email' ) );

    // Exibe o formulário de configurações
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

// Função para criar/atualizar a tabela 'candidaturas' ao ativar o plugin
function jobs_7teengames_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'candidaturas'; // Nome da tabela com prefixo correto
    $charset_collate = $wpdb->get_charset_collate(); // Define o charset para a tabela

    // Definir a consulta SQL para criar/atualizar a tabela
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

    // Inclui o arquivo necessário para executar dbDelta
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    // Executa o dbDelta para criar ou atualizar a tabela
    dbDelta( $sql );

    // Verificar se a tabela foi criada corretamente
    if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
        error_log( "Erro: A tabela $table_name não foi criada corretamente." );
    } else {
        error_log( "Sucesso: A tabela $table_name foi criada ou atualizada." );
    }
}

// Hook para ativar a função de criação da tabela ao ativar o plugin
register_activation_hook( __FILE__, 'jobs_7teengames_create_table' );

// Adiciona o submenu "Candidaturas" ao menu de "Vagas"
function jobs_7teengames_add_submenu() {
    add_submenu_page(
        'edit.php?post_type=vagas', // Associa o submenu à página de "Vagas"
        'Candidaturas',             // Título da página
        'Candidaturas',             // Título do submenu
        'manage_options',           // Capacidade necessária
        'jobs-7teengames-candidaturas', // Slug da página
        'jobs_7teengames_render_candidaturas_page' // Função callback que renderiza a página
    );
}
add_action( 'admin_menu', 'jobs_7teengames_add_submenu' );

// Função que renderiza a página de candidaturas no painel de administração
function jobs_7teengames_render_candidaturas_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'candidaturas'; // Nome da tabela de candidaturas

    // Busca todas as candidaturas
    $results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY data_envio DESC" );

    echo '<div class="wrap">';
    echo '<h1>Candidaturas Recebidas</h1>';
    echo '<table class="widefat fixed" cellspacing="0">';
    echo '<thead><tr><th>Nome</th><th>Email</th><th>Telefone</th><th>Currículo</th><th>Perguntas Customizáveis</th><th>Data de Envio</th></tr></thead>';
    echo '<tbody>';

    foreach ( $results as $row ) {
        echo '<tr>';
        echo '<td>' . esc_html( $row->nome ) . '</td>';
        echo '<td>' . esc_html( $row->email ) . '</td>';
        echo '<td>' . esc_html( $row->telefone ) . '</td>';
        echo '<td><a href="' . esc_url( $row->curriculo_url ) . '" target="_blank">Download</a></td>';

        // Verificar se há perguntas customizadas
        if ( ! empty( $row->perguntas_respostas ) ) {
            // Botão para ver perguntas customizadas
            echo '<td><button class="button view-questions" data-questions="' . esc_attr( $row->perguntas_respostas ) . '">Ver Perguntas</button></td>';
        } else {
            // Se não houver perguntas customizadas, exibe um traço ou espaço vazio
            echo '<td>—</td>';
        }
        
        echo '<td>' . esc_html( $row->data_envio ) . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    
    // Modal que será exibido com as perguntas e respostas
    echo '<div id="questionsModal" style="display:none;">';
    echo '<div class="modal-content">';
    echo '<span id="closeModal" class="close">&times;</span>';
    echo '<div id="questionsContent"></div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

// Função para adicionar o script JavaScript e o CSS para o modal
function jobs_7teengames_enqueue_admin_scripts() {
    ?>
    <style>
        /* Estilos para o modal */
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
            // Quando o botão "Ver Perguntas Customizáveis" for clicado
            $('.view-questions').on('click', function() {
                // Recupera as perguntas e respostas do atributo data
                var questionsData = $(this).data('questions');

                // Separar perguntas e respostas por linha
                var questionsArray = questionsData.split("\n");

                // Cria o HTML para exibir perguntas e respostas formatadas
                var formattedQuestions = '';
                $.each(questionsArray, function(index, question) {
                    if (question.trim() !== '') {
                        formattedQuestions += '<p>' + question + '</p>';
                    }
                });

                // Define o conteúdo no modal
                $('#questionsContent').html(formattedQuestions);

                // Exibe o modal
                $('#questionsModal').fadeIn();
            });

            // Quando o botão de fechar for clicado
            $('#closeModal').on('click', function() {
                $('#questionsModal').fadeOut();
            });

            // Quando o usuário clicar fora do modal, feche o modal
            $(window).on('click', function(event) {
                if (event.target.id === 'questionsModal') {
                    $('#questionsModal').fadeOut();
                }
            });
        });
    </script>
    <?php
}
add_action( 'admin_footer', 'jobs_7teengames_enqueue_admin_scripts' );

// Inicia a sessão, se ainda não estiver ativa
function jobs_7teengames_start_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'jobs_7teengames_start_session');

function jobs_7teengames_enqueue_styles_and_scripts() {
    wp_enqueue_style( 'jobs-7teengames-styles', plugin_dir_url( __FILE__ ) . 'style.css' );

    // Adiciona o script para esconder a mensagem após 10 segundos
    wp_add_inline_script( 'jquery', '
        jQuery(document).ready(function($) {
            setTimeout(function() {
                $("#application-status-message").fadeOut("slow");
            }, 10000); // 10 segundos
        });
    ' );
}
add_action( 'wp_enqueue_scripts', 'jobs_7teengames_enqueue_styles_and_scripts' );