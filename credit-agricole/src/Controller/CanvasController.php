<?php

namespace App\Controller;

use App\Entity\Basket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CanvasController extends AbstractController
{


    #[Route('/save-pdf', name: 'app_save_pdf')]
    public function savePdf(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!empty($data['imageData'])) {
            $imageData = $data['imageData'];

            // Convert the base64 image data to binary
            $imageBinary = base64_decode($imageData);

            // Save the binary data as a PDF file
            $pdfFilePath = $this->getParameter('kernel.project_dir') . '/public/pdf/' . uniqid('canvas_', true) . '.pdf';
            file_put_contents($pdfFilePath, $imageBinary);

            return new JsonResponse(['message' => 'PDF saved successfully', 'pdf_path' => $pdfFilePath]);
        }

        return new JsonResponse(['error' => 'Invalid data'], 400);
    }

}
