<?php

namespace Adilarry\GALib;

class GeneticAlgorithmController
{
    /**
     * Configuration object for this algorithm
     *
     * @var GeneticAlgorithmConfig
     */
    private $config;

    /**
     * Problem-specific algorithm class object
     *
     * @var mixed
     */
    private $algorithm;

    /**
     * Create a new instance of this class
     *
     * @param GeneticAlgorithmConfig $config Configuration object
     * @param mixed Instance of class for problem-specific GA class
     */
    public function __construct($config, $algorithm, $max_generations = 1000)
    {
        $this->config = $config;
        $this->algorithm = $algorithm;
        $this->max_generations = $max_generations;
    }

    /**
     * Run the genetic algorithm
     *
     */
    public function run()
    {
        $algorithm = $this->algorithm;
        $config = $this->config;

        $ga = new GeneticAlgorithm($config, $algorithm);

        $population = $ga->init_population($algorithm);

        $ga->evaluate_population($population, $algorithm);

        $generation_count = 1;

        $fittest = $population->get_fittest(0);

        while (!$algorithm->should_terminate($population) &&
            $generation_count < $this->max_generations) {
            $fittest = $population->get_fittest(0);
            logger("Generation $generation_count:");
            $algorithm->log("Fittest: " . $fittest, $generation_count);

            $population = $ga->crossover_population($population);

            $population = $ga->mutate_population($population);

            $ga->evaluate_population($population, $algorithm);

            $generation_count++;
        }

        return (string) $fittest;
    }
}