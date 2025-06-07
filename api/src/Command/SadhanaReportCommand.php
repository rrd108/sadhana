<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\I18n\FrozenDate;
use Cake\ORM\Locator\LocatorAwareTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SadhanaReportCommand extends Command
{
    use LocatorAwareTrait;

    protected $sadhanasTable;
    protected $usersTable;
    protected $io;

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser->addArgument('month', [
            'help' => 'Month in YYYY-MM format',
            'required' => true,
        ]);

        $parser->addOption('output', [
            'short' => 'o',
            'help' => 'Output file path',
            'default' => 'nvd_sadhana_report_.xlsx',
        ]);

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->io = $io;
        $this->sadhanasTable = $this->fetchTable('Sadhanas');
        $this->usersTable = $this->fetchTable('Users');

        $month = $args->getArgument('month');
        $outputPath = $args->getOption('output');

        // Validate month format
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $io->error('Invalid month format. Please use YYYY-MM format.');
            return 1;
        }

        // Create start and end dates for the month
        $startDate = new FrozenDate($month . '-01');
        $endDate = $startDate->endOfMonth();

        // Get all users
        $users = $this->usersTable->find()
            ->select(['id', 'name'])
            ->order(['name' => 'ASC'])
            ->all();

        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Remove default sheet

        // Create summary sheet
        $summarySheet = $spreadsheet->createSheet();
        $summarySheet->setTitle('Summary');

        // Set headers for summary sheet
        $summarySheet->setCellValue('A1', 'Name');
        $summarySheet->setCellValue('B1', 'Mangala');
        $summarySheet->setCellValue('C1', 'Japa');
        $summarySheet->setCellValue('D1', 'Kirtana');
        $summarySheet->setCellValue('E1', 'Class');
        $summarySheet->setCellValue('F1', 'Reading');

        // Style the header row
        $summarySheet->getStyle('A1:F1')->getFont()->setBold(true);
        $summarySheet->getStyle('A1:F1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E0E0E0');
        $summarySheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $summaryRow = 2;
        $allTotals = [];
        $usersWithData = [];

        foreach ($users as $user) {
            // Get sadhana data for the user
            $sadhanas = $this->sadhanasTable->find()
                ->select([
                    'date',
                    'mangala',
                    'japa',
                    'kirtana',
                    'class',
                    'reading'
                ])
                ->where([
                    'user_id' => $user->id,
                    'date >=' => $startDate,
                    'date <=' => $endDate
                ])
                ->order(['date' => 'ASC'])
                ->all();

            if ($sadhanas->isEmpty()) {
                continue;
            }

            // Calculate totals
            $totals = [
                'mangala' => 0,
                'japa' => 0,
                'kirtana' => 0,
                'class' => 0,
                'reading' => 0
            ];

            foreach ($sadhanas as $sadhana) {
                if ($sadhana->mangala) $totals['mangala']++;
                if ($sadhana->japa) $totals['japa']++;
                if ($sadhana->kirtana) $totals['kirtana']++;
                if ($sadhana->class) $totals['class']++;
                $totals['reading'] += (int)$sadhana->reading;
            }

            // Add to summary sheet
            $summarySheet->setCellValue('A' . $summaryRow, $user->name);
            $summarySheet->setCellValue('B' . $summaryRow, $totals['mangala']);
            $summarySheet->setCellValue('C' . $summaryRow, $totals['japa']);
            $summarySheet->setCellValue('D' . $summaryRow, $totals['kirtana']);
            $summarySheet->setCellValue('E' . $summaryRow, $totals['class']);
            $summarySheet->setCellValue('F' . $summaryRow, $totals['reading']);

            // Store totals and user for later use
            $allTotals[$user->id] = $totals;
            $usersWithData[] = $user;

            $summaryRow++;
        }

        // Add grand totals row
        $summarySheet->setCellValue('A' . $summaryRow, 'Total');
        $summarySheet->setCellValue('B' . $summaryRow, array_sum(array_column($allTotals, 'mangala')));
        $summarySheet->setCellValue('C' . $summaryRow, array_sum(array_column($allTotals, 'japa')));
        $summarySheet->setCellValue('D' . $summaryRow, array_sum(array_column($allTotals, 'kirtana')));
        $summarySheet->setCellValue('E' . $summaryRow, array_sum(array_column($allTotals, 'class')));
        $summarySheet->setCellValue('F' . $summaryRow, array_sum(array_column($allTotals, 'reading')));

        // Style the grand totals row
        $summarySheet->getStyle('A' . $summaryRow . ':F' . $summaryRow)->getFont()->setBold(true);
        $summarySheet->getStyle('A' . $summaryRow . ':F' . $summaryRow)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E0E0E0');

        // Center align all data cells except name column
        $summarySheet->getStyle('B2:F' . $summaryRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $summarySheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create individual sheets only for users with data
        foreach ($usersWithData as $user) {
            // Get sadhana data for the user
            $sadhanas = $this->sadhanasTable->find()
                ->select([
                    'date',
                    'mangala',
                    'japa',
                    'kirtana',
                    'class',
                    'reading'
                ])
                ->where([
                    'user_id' => $user->id,
                    'date >=' => $startDate,
                    'date <=' => $endDate
                ])
                ->order(['date' => 'ASC'])
                ->all();

            // Create worksheet for user
            $worksheet = $spreadsheet->createSheet();
            $worksheet->setTitle(substr($user->name, 0, 31)); // Excel sheet names are limited to 31 chars

            // Set headers
            $worksheet->setCellValue('A1', 'Date');
            $worksheet->setCellValue('B1', 'Mangala');
            $worksheet->setCellValue('C1', 'Japa');
            $worksheet->setCellValue('D1', 'Kirtana');
            $worksheet->setCellValue('E1', 'Class');
            $worksheet->setCellValue('F1', 'Reading');

            // Style the header row
            $worksheet->getStyle('A1:F1')->getFont()->setBold(true);
            $worksheet->getStyle('A1:F1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E0E0E0');

            // Add data
            $row = 2;
            $totals = $allTotals[$user->id];

            // Create a map of existing sadhana entries
            $sadhanaMap = [];
            foreach ($sadhanas as $sadhana) {
                $sadhanaMap[$sadhana->date->format('Y-m-d')] = $sadhana;
            }

            // Add all days of the month
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $dateStr = $currentDate->format('Y-m-d');
                $worksheet->setCellValue('A' . $row, $dateStr);

                if (isset($sadhanaMap[$dateStr])) {
                    $sadhana = $sadhanaMap[$dateStr];
                    $worksheet->setCellValue('B' . $row, $sadhana->mangala ? '✓' : '');
                    $worksheet->setCellValue('C' . $row, $sadhana->japa ? '✓' : '');
                    $worksheet->setCellValue('D' . $row, $sadhana->kirtana ? '✓' : '');
                    $worksheet->setCellValue('E' . $row, $sadhana->class ? '✓' : '');
                    $worksheet->setCellValue('F' . $row, $sadhana->reading);
                } else {
                    // Leave cells empty for days without entries
                    $worksheet->setCellValue('B' . $row, '');
                    $worksheet->setCellValue('C' . $row, '');
                    $worksheet->setCellValue('D' . $row, '');
                    $worksheet->setCellValue('E' . $row, '');
                    $worksheet->setCellValue('F' . $row, '');
                }

                $row++;
                $currentDate = $currentDate->addDays(1);
            }

            // Add summary row
            $worksheet->setCellValue('A' . $row, 'Total');
            $worksheet->setCellValue('B' . $row, $totals['mangala']);
            $worksheet->setCellValue('C' . $row, $totals['japa']);
            $worksheet->setCellValue('D' . $row, $totals['kirtana']);
            $worksheet->setCellValue('E' . $row, $totals['class']);
            $worksheet->setCellValue('F' . $row, $totals['reading']);

            // Style the summary row
            $worksheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
            $worksheet->getStyle('A' . $row . ':F' . $row)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E0E0E0');

            // Center align all data cells except date column
            $worksheet->getStyle('B2:F' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Auto-size columns
            foreach (range('A', 'F') as $col) {
                $worksheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        // Set summary sheet as active
        $spreadsheet->setActiveSheetIndex(0);

        // Save the spreadsheet
        $writer = new Xlsx($spreadsheet);
        $writer->save($outputPath);

        $io->success("Report generated successfully at: $outputPath");
        return 0;
    }
}
