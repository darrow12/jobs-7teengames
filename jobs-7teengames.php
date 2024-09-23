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
