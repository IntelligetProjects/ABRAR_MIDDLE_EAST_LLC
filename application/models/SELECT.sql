
-- Total invoices 
SELECT 
SUM(devinvoice_items.total - IF(devinvoice_items.discount_amount_type='percentage',devinvoice_items.total*devinvoice_items.discount_amount/100,devinvoice_items.discount_amount)) AS total,
SUM((devinvoice_items.total - IF(devinvoice_items.discount_amount_type='percentage',devinvoice_items.total*devinvoice_items.discount_amount/100,devinvoice_items.discount_amount))*devtaxes.percentage*0.01) AS tax
FROM devinvoices
INNER JOIN devinvoice_items
ON devinvoices.id = devinvoice_items.invoice_id 
LEFT JOIN devtaxes
ON devtaxes.id= devinvoice_items.tax_id
WHERE approval_status ='approved' AND devinvoices.deleted=0  AND devinvoice_items.deleted=0

-- Total salse return

SELECT 
SUM((devinvoice_items.rate * devsale_return_items.quantity) - IF(devinvoice_items.discount_amount_type='percentage',devinvoice_items.rate * devsale_return_items.quantity*devinvoice_items.discount_amount/100,devinvoice_items.discount_amount)) AS total,
SUM(((devinvoice_items.rate * devsale_return_items.quantity) - IF(devinvoice_items.discount_amount_type='percentage',devinvoice_items.rate * devsale_return_items.quantity*devinvoice_items.discount_amount/100,devinvoice_items.discount_amount))*devtaxes.percentage*0.01) AS tax
FROM devsale_returns
INNER JOIN devinvoices 
ON devsale_returns.invoice_id = devinvoices.id 
INNER JOIN devsale_return_items
ON devsale_return_items.sale_return_id=devsale_returns.id
INNER JOIN  devinvoice_items  
ON devinvoice_items.id = devsale_return_items.invoice_item_id
LEFT JOIN devtaxes
ON devtaxes.id= devinvoice_items.tax_id
WHERE devsale_returns.`status` ='approved' AND devsale_returns.deleted=0  AND devsale_return_items.deleted=0 AND devinvoice_items.deleted=0

-- Total Cost of Goods 
SELECT 
SUM(devinvoice_items.cost * devinvoice_items.quantity) AS total_cost
FROM devinvoices
INNER JOIN devinvoice_items
ON devinvoices.id = devinvoice_items.invoice_id 
WHERE approval_status ='approved' AND devinvoices.deleted=0  AND devinvoice_items.deleted=0

-- TOTAL expences 
SELECT SUM(devexpenses.amount) AS total,
SUM((devexpenses.amount * devtaxes.percentage)*0.01) AS tax
FROM devexpenses 
LEFT JOIN  devtaxes 
ON  devexpenses.tax_id=devtaxes.id
WHERE devexpenses.status ="approved"

