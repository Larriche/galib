<?php

include('CrossoverTypes.php');
include('GeneticAlgorithm.php');
include('Individual.php');
include('MutationTypes.php');
include('Population.php');
include('GeneticAlgorithmConfig.php');
include('GeneticAlgorithmController.php');

use Adilarry\GALib\CrossoverTypes;
use Adilarry\GALib\GeneticAlgorithm;
use Adilarry\GALib\Population;
use Adilarry\GALib\Individual;
use AdiLarry\GALib\MutationTypes;
use Adilarry\GALib\GeneticAlgorithmConfig;
use Adilarry\GALib\GeneticAlgorithmController;

class AlgorithmController
{
    public $chromosome_length = 10;

    public function create_individual()
    {
        $chromosome = [];

        for ($i = 0; $i < $this->chromosome_length; $i++) {
            $chromosome[$i] = mt_rand(0, 1);
        }

        return $chromosome;
    }

    public function calculate_fitness($individual)
    {
        $correct_genes = 0;

        for ($i = 0; $i < $individual->get_chromosome_length(); $i++) {
            if ($individual->get_gene($i) == 1) {
                $correct_genes += 1;
            }
        }

        $fitness = (double) $correct_genes / $individual->get_chromosome_length();

        $individual->set_fitness($fitness);

        return $fitness;
    }

    public function create_random_individual()
    {
        print 'called random';
        return [];
    }

    public function should_terminate($population)
    {
        return $population->get_fittest(0)->get_fitness() == 1;
    }
}

$algorithm = new AlgorithmController();

$config = new GeneticAlgorithmConfig();
$config->set_crossover_rate(0.9);
$config->set_mutation_rate(0.05);
$config->set_population_size(100);
$config->set_mutation_rate(0.5);
$config->set_elitism_count(2);
$config->set_tournament_size(50);
$config->set_temperature(1);
$config->set_cooling_rate(0.01);
$config->set_crossover_type(CrossoverTypes::UNIFORM);
$config->set_mutation_type(MutationTypes::BIT_FLIP);
$config->set_adaptive_mutation(true);

$ga = new GeneticAlgorithmController($config, $algorithm);
$ga->run();