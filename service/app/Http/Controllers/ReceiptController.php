<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Repositories\Contracts\ReceiptRepository;
use Illuminate\Http\Request;
use App\Transformers\ReceiptTransformer;

class ReceiptController extends Controller
{
    /**
     * Instance of ReceiptRepository.
     *
     * @var ReceiptRepository
     */
    private $receiptRepository;

    /**
     * Instanceof ReceiptTransformer.
     *
     * @var ReceiptTransformer
     */
    private $receiptTransformer;

    /**
     * Constructor.
     *
     * @param ReceiptRepository $receiptRepository
     * @param ReceiptTransformer $receiptTransformer
     */
    public function __construct(ReceiptRepository $receiptRepository, ReceiptTransformer $receiptTransformer)
    {
        $this->receiptRepository = $receiptRepository;
        $this->receiptTransformer = $receiptTransformer;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $receipts = $this->receiptRepository->findBy($request->all());

        return $this->respondWithCollection($receipts, $this->receiptTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function show($id)
    {
        $receipt = $this->receiptRepository->findOne($id);

        if (!$receipt instanceof Receipt) {
            return $this->sendNotFoundResponse("The receipt with id {$id} doesn't exist");
        }

        // Authorization
        $this->authorize('show', $receipt);

        return $this->respondWithItem($receipt, $this->receiptTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function store(Request $request)
    {
        // Validation
        $validatorResponse = $this->validateRequest($request, $this->storeRequestValidationRules($request));

        // Send failed response if validation fails
        if ($validatorResponse !== true) {
            return $this->sendInvalidFieldResponse($validatorResponse);
        }

        // Make sure the file uploaded properly
        if ($request->file('receipt')->isValid() !== true) {
            return $this->sendCustomResponse(500, 'Error occurred on uploading Receipt image');
        }

        $data = $request->except('receipt');
        $data['filePath'] = $request->file('receipt')->getRealPath(); // Temporary path, it will be moved once saved
        $data['fileExt'] = $request->file('receipt')->guessExtension();

        $receipt = $this->receiptRepository->save($data);

        if (!$receipt instanceof Receipt) {
            return $this->sendCustomResponse(500, 'Error occurred on creating Receipt');
        }

        return $this->setStatusCode(201)->respondWithItem($receipt, $this->receiptTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validatorResponse = $this->validateRequest($request, $this->updateRequestValidationRules($request));

        // Send failed response if validation fails
        if ($validatorResponse !== true) {
            return $this->sendInvalidFieldResponse($validatorResponse);
        }

        $receipt = $this->receiptRepository->findOne($id);

        if (!$receipt instanceof Receipt) {
            return $this->sendNotFoundResponse("The receipt with id {$id} doesn't exist");
        }

        // Authorization
        $this->authorize('update', $receipt);


        $receipt = $this->receiptRepository->update($receipt, $request->all());

        return $this->respondWithItem($receipt, $this->receiptTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function destroy($id)
    {
        $receipt = $this->receiptRepository->findOne($id);

        if (!$receipt instanceof Receipt) {
            return $this->sendNotFoundResponse("The receipt with id {$id} doesn't exist");
        }

        // Authorization
        $this->authorize('destroy', $receipt);

        $this->receiptRepository->delete($receipt);

        return response()->json(null, 204);
    }

    /**
     * Store Request Validation Rules
     *
     * @param Request $request
     * @return array
     */
    private function storeRequestValidationRules(Request $request)
    {
        $categories = [ // This shouldn't be defined in controller, it should be in the model instead.
            'airfare',
            'vehicle rental',
            'fuel',
            'lodging',
            'meals',
            'phone',
            'entertainment',
            'weapons',
            'other'
        ];
       return [
           'userId'     => 'required|exists:users,uid',
           'receipt'    => 'required|file|mimes:jpeg,png|max:10000',  // 10 MB
           'name'       => 'required|string|max:32',
           'notes'      => 'string|max:1000',
           'category'   => 'required|in:' . implode(',', $categories),
        ];
    }

    /**
     * Update Request validation Rules
     *
     * @param Request $request
     * @return array
     */
    private function updateRequestValidationRules(Request $request)
    {
        return [
        ];
    }
}
