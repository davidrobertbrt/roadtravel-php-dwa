<?php

class HitReport
{
    private $pdf;

    function __construct()
    {
        $this->pdf = new FPDF(); //early instantiation
    }

    function generateReport($stats) {
        $this->pdf->AddPage();

        // Report title
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(0, 10, 'Hits Statistics Summary', 0, 1, 'L');
        $this->pdf->Ln(10);

        // Statistics table
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(40, 10, 'Statistic', 1, 0, 'L');
        $this->pdf->Cell(50, 10, 'Value', 1, 1, 'L');
        $this->pdf->SetFont('Arial', '', 12);
        $this->pdf->Cell(40, 10, 'Total Hits', 1, 0, 'L');
        $this->pdf->Cell(50, 10, $stats['totalHits'], 1, 1, 'L');
        $this->pdf->Cell(40, 10, 'Unique Visitors', 1, 0, 'L');
        $this->pdf->Cell(50, 10, $stats['uniqueVisitors'], 1, 1, 'L');
        $this->pdf->Cell(40, 10, 'Mean Hits per Visitor', 1, 0, 'L');
        $this->pdf->Cell(50, 10, $stats['meanHitsPerVisitor'], 1, 1, 'L');
        $this->pdf->Cell(40, 10, 'Maximum Hits per Day', 1, 0, 'L');
        $this->pdf->Cell(50, 10, $stats['maxHitsPerDay'], 1, 1, 'L');
        $this->pdf->Cell(40, 10, 'Average Hits per Day', 1, 0, 'L');
        $this->pdf->Cell(50, 10, $stats['averageHitsPerDay'], 1, 1, 'L');
        $this->pdf->Cell(40, 10, 'Standard Deviation', 1, 0, 'L');
        $this->pdf->Cell(50, 10, $stats['stdDevHitsPerDay'], 1, 1, 'L');
        $this->pdf->Ln(10);

        // Generate the graph
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(0, 10, 'Hits per Day', 0, 1, 'L');
        
        $graphWidth = 160;
        $graphHeight = 80;
        $margin = 20;
        $xStart = $this->pdf->GetX();
        $yStart = $this->pdf->GetY();

        // Calculate the scale factor for the bars
        $maxHits = max($stats['hitsPerDay']);
        $scaleFactor = $graphHeight / $maxHits;

        // Draw the bars for each day
        $barWidth = ($graphWidth - 2 * $margin) / count($stats['hitsPerDay']);
        $x = $xStart + $margin;
        foreach ($stats['hitsPerDay'] as $hits) {
            $barHeight = $hits * $scaleFactor;
            $this->pdf->Rect($x, $yStart + $graphHeight - $barHeight, $barWidth, $barHeight, 'F');
            $x += $barWidth;
        }

        // Draw the axis and labels
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Line($xStart + $margin, $yStart, $xStart + $margin, $yStart + $graphHeight); // Y-axis
        $this->pdf->Line($xStart + $margin, $yStart + $graphHeight, $xStart + $graphWidth, $yStart + $graphHeight); // X-axis

        // Add labels for each day
        $x = $xStart + $margin;
        $labelMargin = 5;
        foreach ($stats['hitsPerDay'] as $date => $hits) {
            $this->pdf->Text($x + $barWidth / 2, $yStart + $graphHeight + $labelMargin, substr($date, 5)); // Display only the month and day
            $x += $barWidth;
        }

        $this->pdf->Ln($graphHeight + 2 * $labelMargin);

        // Output the PDF
        $this->pdf->Output('hits_report.pdf', 'I');
    }
    
}