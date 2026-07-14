@props([
    'company',
    'aiProfile' => null,
    'websiteAnalysis' => null,
    'aiSalesConsultant' => null,
    'contacts' => collect(),
    'tasks' => collect(),
    'meetings' => collect(),
    'calls' => collect(),
    'notes' => collect(),
    'documents' => collect(),
])

<x-crm.company-ai-insights
    :company="$company"
    :ai-profile="$aiProfile"
    :website-analysis="$websiteAnalysis"
    :ai-sales-consultant="$aiSalesConsultant"
    :contacts="$contacts"
    :tasks="$tasks"
    :meetings="$meetings"
    :calls="$calls"
    :notes="$notes"
    :documents="$documents"
/>