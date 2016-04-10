<?php

namespace Command;

use Command\Source\Chinaz;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlerCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('province', InputArgument::REQUIRED, '省份'),
                new InputArgument('start', InputArgument::REQUIRED, '起始地址'),
                new InputArgument('end', InputArgument::REQUIRED, '结束地址'),
            ))
            ->setName('icp:license:crawler')
            ->setDescription('抓取备案信息')
            ->setHelp(<<<'EOF'
命令格式：
    bin/console icp:license:crawler 省份代号 起始号码 结束号码
例如：
    bin/console icp:license:crawler 京 030173 030273
EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = new Chinaz(new Client());
        $province = $input->getArgument('province');
        $start = $input->getArgument('start');
        $end = $input->getArgument('end');

        for ($i = $start; $i <= $end; $i++) {
            $data = $source->crawler(sprintf('%sICP证%0' . strlen($start) . 'd号', $province, $i));
            if ($data) {
                print_r($data);
            }
        }
    }

}